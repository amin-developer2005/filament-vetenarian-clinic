<?php

namespace App\Filament\Pages\Concerns;

use App\Enums\AnimalGender;
use Closure;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Enums\TextSize;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

trait HasProfileComponents
{
    protected function fetchAvatarProfileComponent(string $url, ?string $alt = ''): Image
    {
        return Image::make($url, $alt)
            ->alignCenter()
            ->inlineLabel()
            ->maxWidth(Width::Full);
    }

    protected function fetchFirstNameProfileComponent(string | Htmlable | Closure | null $firstName): Text
    {
        return Text::make($firstName)
            ->copyable()
            ->size(TextSize::Medium)
            ->inlineLabel();
    }

    protected function fetchSurnameProfileComponent(string | Htmlable | Closure | null $surname): Text
    {
        return Text::make($surname)
            ->copyable()
            ->size(TextSize::Medium)
            ->inlineLabel();
    }

    protected function fetchUserNameProfileComponent(string | Htmlable | Closure | null $username): Text
    {
        return Text::make($username)
            ->copyable()
            ->size(TextSize::Medium)
            ->inlineLabel();
    }

    protected function fetchEmailProfileComponent(string $email): Text
    {
        return Text::make($email)
            ->copyable()
            ->size(TextSize::Medium)
            ->inlineLabel();
    }

    protected function fetchMobileProfileComponent(int | string | null $mobile): Text
    {
        return Text::make($mobile)
            ->copyable()
            ->size(TextSize::Medium)
            ->inlineLabel();
    }

    protected function fetchAvatarProfileFormComponent(): FileUpload
    {
        return FileUpload::make('avatar')
            ->label(__('auth/pages/profile/edit.form.avatar.label'))
            ->nullable()
            ->directory('avatars/profiles')
            ->image()
            ->imageEditor();
    }

    protected function fetchFirstNameProfileFormComponent(): TextInput
    {
        return TextInput::make('first_name')
            ->label(__('auth/pages/profile/edit.form.first_name.label'))
            ->required()
            ->string()
            ->maxLength(255)
            ->autofocus();
    }

    protected function fetchSurnameProfileFormComponent(): TextInput
    {
        return TextInput::make('name')
            ->label(__('auth/pages/profile/edit.form.surname.label'))
            ->required()
            ->string()
            ->maxLength(255);
    }

    protected function fetchUserNameProfileFormComponent(): TextInput
    {
        return TextInput::make('name')
            ->label(__('auth/pages/profile/edit.form.username.label'))
            ->required()
            ->string()
            ->maxLength(255);
    }

    protected function fetchEmailProfileFormComponent(): TextInput
    {
        return TextInput::make('email')
            ->label(__('auth/pages/profile/edit.form.email.label'))
            ->required()
            ->email()
            ->maxLength(255)
            ->unique(ignoreRecord: true)
            ->live(debounce: 500);
    }

    protected function fetchMobileProfileFormComponent(): TextInput
    {
        return TextInput::make('mobile')
            ->label(__('auth/pages/profile/edit.form.mobile.label'))
            ->required()
            ->integer()
            ->regex('/^[0-9]{,11}$/');
    }

    protected function fetchBirthDateProfileFormComponent(): DatePicker
    {
        return DatePicker::make('birth_date')
            ->label(__('auth/pages/profile/edit.form.birth_date.label'))
            ->required()
            ->date()
            ->displayFormat('M d, Y')
            ->closeOnDateSelection()
            ->native(false);
    }

    protected function fetchGenderProfileFormComponent(): Select
    {
        return Select::make('gender')
            ->label(__('auth/pages/profile/edit.form.gender.label'))
            ->options(AnimalGender::class)
            ->required()
            ->native(false);
    }

    protected function fetchAddressProfileFormComponent(): Textarea
    {
        return Textarea::make('address')
            ->label(__('auth/pages/profile/edit.form.address.label'))
            ->nullable()
            ->rows(2);
    }

    protected function fetchPasswordProfileFormComponent(): TextInput
    {
        return TextInput::make('password')
            ->label(__('auth/pages/edit-profile.form.password.label'))
            ->validationAttribute(__('auth/pages/profile/edit.form.password.validation_attribute'))
            ->required()
            ->string()
            ->maxLength(255)
            ->revealable(filament()->arePasswordsRevealable())
            ->rule(Password::default())
            ->showAllValidationMessages()
            ->dehydrated(fn (string $state): bool => filled($state))
            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
            ->autocomplete('new-password')
            ->live(debounce: 500)
            ->same('passwordConfirmation');
    }

    protected function fetchPasswordConfirmationProfileFormComponent(): TextInput
    {
        return TextInput::make('passwordConfirmation')
            ->label(__('auth/pages/profile/edit.form.password_confirmation.label'))
            ->validationAttribute(__('auth/pages/edit-profile.form.password_confirmation.validation_attribute'))
            ->required()
            ->string()
            ->maxLength(255)
            ->revealable(filament()->arePasswordsRevealable())
            ->visible(fn (Get $get): bool => filled($get('password')))
            ->autocomplete('new-password')
            ->dehydrated(false);
    }

    protected function fetchCurrentPasswordProfileFormComponent(): TextInput
    {
        return TextInput::make('currentPassword')
            ->label(__('auth/pages/profile/edit.form.current_password.label'))
            ->validationAttribute(__('auth/pages/edit-profile.form.current_password.validation_attribute'))
            ->belowContent(__('auth/pages/edit-profile.form.current_password.below_content'))
            ->required()
            ->password()
            ->currentPassword(guard: Filament::getAuthGuard())
            ->revealable(filament()->arePasswordsRevealable())
            ->visible(
                fn (Get $get): bool => filled($get('password')) || ($get('email') !== $this->fetchProfile()->getAttributeValue('email'))
            )
            ->autocomplete('new-password')
            ->dehydrated(false);
    }
}
