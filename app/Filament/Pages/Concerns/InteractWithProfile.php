<?php

namespace App\Filament\Pages\Concerns;

use App\Models\Profile;
use App\Models\User;
use Filament\Auth\Notifications\NoticeOfEmailChangeRequest;
use Filament\Auth\Notifications\VerifyEmailChange;
use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Support\Facades\Notification;
use League\Uri\Components\Query;


trait InteractWithProfile
{
    protected Profile $profile {
        set => $this->profile = $value;
        get {
            return $this->profile ?? $this->resolveProfile();
        }
    }

    protected User $user {
        set => $this->user = $value;
        get {
            return $this->user ?? $this->resolveUser();
        }
    }

    /**
     * @var string<Model>
     */
    protected string $model = Profile::class;


    public function resolveProfile(): Profile
    {
        $user = $this->fetchUser();
        $profile = $user->profile;

        if (! $profile) {
            throw new ModelNotFoundException()->setModel(
                $this->model,
            );
        }

        return $profile;
    }

    public function resolveUser(): User|Authenticatable
    {
        $user = Filament::auth()->user();

        if (! $user) {
            throw new ModelNotFoundException();
        }

        return $user;
    }

    public function fetchProfile(): Profile
    {
        if (isset($this->profile)) {
            return $this->profile;
        }

        return $this->profile = $this->resolveProfile();
    }

    public function fetchUser(): User|Authenticatable
    {
        if (isset($this->user)) {
            return $this->user;
        }

        $user = Filament::auth()->user();

        return $this->user = $user;
    }

    public function fetchModel(): string
    {
        return $this->model;
    }

    public function hasProfile(): bool
    {
        return filled($this->profile);
    }

    protected function fetchProfileUpdateNotification(): ?FilamentNotification
    {
        $title = $this->fetchProfileUpdatedNotificationTitle();

        if (blank($title)) {
            return null;
        }

        return FilamentNotification::make()
            ->title($title)
            ->success();
    }

    protected function fetchProfileUpdatedNotificationTitle(): ?string
    {
        return __('auth/pages/profile/edit.notifications.updated.title');
    }

    protected function fetchRedirectUrl(?Panel $panel = null): ?string
    {
        $panel ??= Filament::getCurrentOrDefaultPanel();

        return $this->fetchProfileUrl(panel: $panel);
    }

    protected function sendEmailChangeVerification(MustVerifyEmail|Model $user, string $newEmail): void
    {
        if ($user->getAttributeValue('email') === $newEmail) {
            return;
        }

        $verifyEmailChangeNotification = app(VerifyEmailChange::class);
        $verifyEmailChangeNotification->url = Filament::getVerifyEmailChangeUrl($user, $newEmail);

        $verificationSignature = Query::new($verifyEmailChangeNotification)->get('signature');

        cache()->put($verificationSignature, true, now()->addHour());

        $user->notify(
            new NoticeOfEmailChangeRequest(
                $newEmail,
                Filament::getBlockEmailChangeVerificationUrl($user, $newEmail, $verificationSignature)
            ));

        $newEmailRecipient = $this->getEmailChangeVerificationRecipientWithNewEmail($user, $verifyEmailChangeNotification, $newEmail);

        Notification::route('mail', $newEmailRecipient)
            ->notify($verifyEmailChangeNotification);

        if ($notification = $this->fetchEmailChangeVerificationSentVerification()) {
            $notification->send();
        }

        $this->data[]['email'] = $user->getAttributeValue('email');
    }

    /**
     * @param MustVerifyEmail|Model $user
     * @param VerifyEmailChange $notification
     * @param string $newEmail
     * @return array<string, string>
     */
    protected function getEmailChangeVerificationRecipientWithNewEmail(MustVerifyEmail|Model $user, VerifyEmailChange $notification, string $newEmail): string
    {
        if (! method_exists($user, 'routeNotificationForMail')) {
            return $newEmail;
        }

        $recipient = $user->routeNotificationForMail($notification);
        $currentEmail = $user->getAttributeValue('email');

        if (
            (! is_array($recipient)) ||
            (! in_array($currentEmail, $recipient))
        ) {
            return $newEmail;
        }

        return [$newEmail => $recipient[$currentEmail]];
    }

    protected function fetchEmailChangeVerificationSentVerification(string $newEmail): ?FilamentNotification
    {
        return FilamentNotification::make()
            ->title(__(
                'auth/pages/profile/edit.email_change_verification_sent.title',
                ['email' => $newEmail]
            ))
            ->body(__(
                'auth/pages/profile/edit.email_change_verification_sent.body',
                ['email' => $newEmail]
            ))
            ->success();
    }
}
