<?php

namespace App\Filament\Owner\Pages\Profile\Schemas;

use App\Enums\ProfileGender;
use App\Filament\Pages\Concerns\HasProfileRoutes;
use App\Filament\Pages\Concerns\InteractWithProfile;
use App\Models\Profile;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Page;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\Width;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Throwable;


class EditProfileSchema extends Page
{
    use CanUseDatabaseTransactions;
    use HasProfileRoutes;
    use InteractsWithForms;
    use InteractWithProfile;

    protected static string $routePath = '/profile/edit';

    public static function isDiscovered(): bool
    {
        return false;
    }


    protected string $personalStatePath = 'personalData';

    protected string $contactStatePath = 'contactData';

    protected string $avatarStatePath = 'avatarData';

    public array $personalData = [];

    public array $contactData = [];

    public array $avatarData = [];

    protected static ?string $slug = '/profile/edit';

    public static function fetchRoutePath(): string
    {
        return static::$routePath;
    }

    public function getTitle(): string|Htmlable
    {
        return __('owner/edit-profile.title');
    }

    public function getHeading(): string|Htmlable|null
    {
        return __('owner/edit-profile.heading');
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->fetchProfileCancelAction(),
        ];
    }

    public function getSubheading(): string|Htmlable|null
    {
        return __('owner/edit-profile.subheading');
    }

    public function mount(): void
    {
        $this->user = $this->resolveUser();
        $this->profile = $this->resolveProfile();

        $this->fillAvatarProfileForm();
        $this->fillPersonalProfileForm();
        $this->fillContactProfileForm();
    }


    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->avatarForm(),
                $this->personalForm(),
                $this->contactForm(),
            ]);
    }

    public function personalForm(): Form
    {
        return Form::make()
            ->id('personalForm')
            ->statePath($this->personalStatePath)
            ->components([
                Section::make(__('owner/edit-profile.sections.personal.title'))
                    ->description(__('owner/edit-profile.sections.personal.description'))
                    ->icon('heroicon-o-user')
                    ->iconColor('success')
                    ->columns(2)
                    ->headerActions([
                        $this->fetchPersonalProfileSaveAction(),
                    ])
                    ->schema([
                        TextInput::make('first_name')
                            ->label(__('owner/edit-profile.schema.form.components.sections.personal.first_name.label'))
                            ->required()
                            ->string()
                            ->maxLength(255),

                        TextInput::make('surname')
                            ->label(__('owner/edit-profile.schema.form.components.sections.personal.surname.label'))
                            ->required()
                            ->string()
                            ->maxLength(255),

                        Select::make('gender')
                            ->label(__('owner/edit-profile.schema.form.components.sections.personal.gender.label'))
                            ->options(ProfileGender::class)
                            ->native(false)
                            ->required(),

                        DatePicker::make('birth_date')
                            ->label(__('owner/edit-profile.schema.form.components.sections.personal.birth_date.label'))
                            ->required()
                            ->closeOnDateSelection()
                            ->date()
                            ->displayFormat('Y-M-d')
                            ->native(false),
                    ]),
            ]);
    }

    public function contactForm(): Form
    {
        return
            Form::make()
                ->id('contactForm')
                ->statePath($this->contactStatePath)
                ->schema([
                    Section::make(__('owner/edit-profile.sections.contact.title'))
                        ->description(__('owner/edit-profile.sections.contact.description'))
                        ->icon('heroicon-o-map-pin')
                        ->iconColor('warning')
                        ->columns(2)
                        ->headerActions([
                            $this->fetchContactProfileSaveAction(),
                        ])
                        ->schema([
                            TextInput::make('email')
                                ->label(__('owner/edit-profile.schema.form.components.sections.contact.email.label'))
                                ->required()
                                ->email()
                                ->unique(ignoreRecord: true)
                                ->live(),

                            TextInput::make('mobile')
                                ->label(__('owner/edit-profile.schema.form.components.sections.contact.mobile.label'))
                                ->tel()
                                ->mask('09999999999')
                                ->maxLength(11)
                                ->required(),
                            Textarea::make('address')
                                ->label(__('owner/edit-profile.schema.form.components.sections.contact.address.label'))
                                ->nullable()
                                ->columnSpanFull()
                                ->rows(3),

                        ]),
                ]);
    }

    public function avatarForm(): Form
    {
        return Form::make()
            ->id('avatarForm')
            ->statePath($this->avatarStatePath)
            ->schema([
                Section::make(__('owner/edit-profile.sections.avatar.title'))
                    ->description(__('owner/edit-profile.sections.avatar.description'))
                    ->icon('heroicon-o-camera')
                    ->iconColor('primary')
                    ->columns(2)
                    ->headerActions([
                        $this->fetchAvatarProfileSaveAction(),
                        $this->fetchAvatarProfileClearAction(),
                    ])
                    ->schema([
                        FileUpload::make('avatar')
                            ->label(__('owner/edit-profile.schema.form.components.sections.avatar.avatar.label'))
                            ->image()
                            ->live()
                            ->maxWidth(Width::FiveExtraLarge)
                            ->visibility('public')
                            ->disk('public')
                            ->directory('profiles/avatars')
                            ->imageEditor()
                            ->columnSpanFull()
                            ->previewable()
                            ->downloadable()
                            ->openable(),
                    ]),
            ])->columnSpanFull();
    }

    protected function fetchPersonalProfileSaveAction(): Action
    {
        return Action::make('savePersonalInformation')
            ->label(__('owner/edit-profile.actions.sections.personal.save.title'))
            ->icon(Heroicon::OutlinedCheck)
            ->color('primary')
            ->iconSize(IconSize::TwoExtraLarge)
            ->action('savePersonalInformation');
    }

    protected function fetchContactProfileSaveAction(): Action
    {
        return Action::make('saveContactInformation')
            ->label(__('owner/edit-profile.actions.sections.contact.save.title'))
            ->icon(Heroicon::OutlinedCheck)
            ->color('primary')
            ->iconSize(IconSize::TwoExtraLarge)
            ->action('saveContactInformation');
    }

    protected function fetchAvatarProfileSaveAction(): Action
    {
        return Action::make('saveAvatar')
            ->label(__('owner/edit-profile.actions.sections.avatar.save.title'))
            ->icon(Heroicon::OutlinedPhoto)
            ->color('primary')
            ->disabled(fn(Get $get) => blank($get('avatar')))
            ->iconSize(IconSize::TwoExtraLarge)
            ->action('saveAvatar');
    }

    protected function fetchAvatarProfileClearAction(): Action
    {
        return Action::make('clearAvatar')
            ->label(__('owner/edit-profile.actions.sections.avatar.clear.title'))
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->iconSize(IconSize::TwoExtraLarge)
            ->visible(fn() => $this->profile->hasAvatar())
            ->requiresConfirmation()
            ->modalHeading(__('owner/edit-profile.modals.sections.avatar.clear.heading'))
            ->modalDescription(
                __('owner/edit-profile.modals.sections.avatar.clear.description')
            )
            ->modalSubmitActionLabel(__('owner/edit-profile.modals.sections.avatar.clear.submit'))
            ->modalCancelActionLabel(__('owner/edit-profile.modals.sections.avatar.clear.cancel'))
            ->action('clearAvatar');
    }

    protected function fetchProfileCancelAction(): Action
    {
        return $this->backAction();
    }

    protected function backAction(): Action
    {
        $url = url()->previous();

        return Action::make('back')
            ->icon(Heroicon::OutlinedArrowRight)
            ->iconSize(IconSize::TwoExtraLarge)
            ->color('gray')
            ->label(__('owner/edit-profile.actions.back'))
            ->url($url);
    }

    protected function fillAvatarProfileForm(): void
    {
        $this->callHook('beforeFill');

        $this->avatarData = [
            'avatar' => $this->profile->avatar,
        ];

        $this->callHook('afterFill');
    }

    protected function fillPersonalProfileForm()
    {
        $this->callHook('beforeFill');

        $this->personalData = [
            'first_name' => $this->profile->first_name,
            'surname' => $this->profile->surname,
            'gender' => $this->profile->gender,
            'birth_date' => $this->profile->birth_date,
        ];

        $this->callHook('afterFill');
    }

    protected function fillContactProfileForm()
    {
        $this->callHook('beforeFill');

        $this->contactData = [
            'email' => $this->user->email,
            'mobile' => $this->profile->mobile,
            'address' => $this->profile->address,
        ];

        $this->callHook('afterFill');
    }

    public function savePersonalInformation(): void
    {
        try {
            $this->beginDatabaseTransaction();

            $data = $this->mutateFormDataBeforeSave(
                $this->personalData
            );

            $this->handlePersonalInformationUpdate($this->profile, $data);
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction()
                ? $this->rollbackDatabaseTransaction()
                : $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }

        $this->commitDatabaseTransaction();

        $this->fetchSavePersonalInformationNotification()?->send();
    }

    public function saveContactInformation(): void
    {
        try {
            $this->beginDatabaseTransaction();

            $data = $this->mutateFormDataBeforeSave(
                $this->contactData
            );

            $this->handleContactInformationUpdate($this->profile, $this->user, $data);
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction()
                ? $this->rollbackDatabaseTransaction()
                : $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }

        $this->commitDatabaseTransaction();

        $this->fetchSaveContactInformationNotification()?->send();
    }

    public function saveAvatar(): void
    {
        try {
            $this->beginDatabaseTransaction();

            $data = $this->mutateFormDataBeforeSave(
                $this->avatarData
            );

            $this->handleAvatarUpdate($this->profile, $data);
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction()
                ? $this->rollbackDatabaseTransaction()
                : $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }

        $this->commitDatabaseTransaction();

        $this->fetchSaveAvatarNotification()?->send();
    }

    public function clearAvatar(): void
    {
        if (!$this->profile->hasAvatar()) {
            return;
        }

        try {
            $this->beginDatabaseTransaction();

            $avatar = $this->profile->avatar;

            Storage::disk('public')->delete($avatar);
            $this->avatarData['avatar'] = null;

            $this->profile->update($this->avatarData);
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction()
                ? $this->rollbackDatabaseTransaction()
                : $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }

        $this->commitDatabaseTransaction();

        $this->fetchClearAvatarNotification()?->send();
    }

    protected function fetchClearAvatarNotification(): ?Notification
    {
        $title = $this->fetchClearAvatarNotificationTitle();

        if (!$title) {
            return null;
        }

        return Notification::make()
            ->title($title)
            ->success();
    }

    protected function fetchClearAvatarNotificationTitle(): ?string
    {
        return __('owner/edit-profile.notifications.sections.avatar.actions.clear.title');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    protected function handlePersonalInformationUpdate(Profile $profile, array $data): Profile
    {
        $profile->update($data);

        return $profile;
    }

    protected function fetchSavePersonalInformationNotification(): ?Notification
    {
        $title = $this->fetchSavePersonalInformationNotificationTitle();

        if (!$title) {
            return null;
        }

        return Notification::make()
            ->title($title)
            ->success();
    }

    protected function fetchSavePersonalInformationNotificationTitle(): ?string
    {
        return __('owner/edit-profile.notifications.sections.personal.actions.save.title');
    }

    protected function handleContactInformationUpdate(Profile $profile, User $user, array $data): array
    {
        if ($user->getAttributeValue('email') !== $data['email']) {
            $user->update([
                'email' => $data['email'],
            ]);
        }

        $profile->update($data);

        return [$profile, $user];
    }

    protected function fetchSaveContactInformationNotification(): ?Notification
    {
        $title = $this->fetchSaveContactInformationNotificationTitle();

        if (!$title) {
            return null;
        }

        return Notification::make()
            ->title($title)
            ->success();
    }

    protected function fetchSaveContactInformationNotificationTitle(): ?string
    {
        return __('owner/edit-profile.notifications.sections.contact.actions.save.title');
    }

    protected function handleAvatarUpdate(Profile $profile, array $data): Profile
    {
        $avatar = collect($data['avatar'])->first();
        $newPath = $avatar->store('profiles/avatars', 'public');

        $profile->update([
            'avatar' => $newPath,
        ]);

        return $profile;
    }

    protected function fetchSaveAvatarNotification(): ?Notification
    {
        $title = $this->fetchSaveAvatarNotificationTitle();

        if (!$title) {
            return null;
        }

        return Notification::make()
            ->title($title)
            ->success();
    }

    protected function fetchSaveAvatarNotificationTitle(): ?string
    {
        return __('owner/edit-profile.notifications.sections.avatar.actions.save.title');
    }
}
