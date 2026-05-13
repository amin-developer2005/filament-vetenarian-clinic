<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Pages\Concerns\InteractWithTenantClinic;
use App\Models\Clinic;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;


class RegisterClinic extends RegisterTenant
{
    use InteractWithTenantClinic;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data {
        set => $this->data = $value;
        get => $this->data ?? null;
    }

    public ?string $name {
        set => $this->name = $value;
        get => $this->name ?? null;
    }

    protected string $formId {
        set => $this->formId = $value;
        get => $this->formId ?? 'form';
    }

    protected string $livewireSubmitFormMethodName {
        set => $this->livewireSubmitFormMethodName = $value;
        get => $this->livewireSubmitFormMethod ?? 'register';
    }

    protected bool $hasFormActionsFullWidth {
        set => $this->hasFormActionsFullWidth = $value;
        get => $this->hasFormActionsFullWidth ?? true;
    }

    public function mount(): void
    {
        $this->authorizeAccess();

        $this->form->fill();
    }

    public static function getLabel(): string
    {
        return "ثبت کلینیک جدید";
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->schema([
                $this->getFormContentComponents(),
            ]);
    }

    protected function authorizeAccess(): void
    {
        abort_unless(
            $this->canAccess($this->getTenant() ?? $this->getTenantModel(), 'create'),
            403
        );
    }

    public function register(): void
    {
        try {
            $data = $this->mutateFormDataBeforeRegister(['name' => $this->name]);

            $this->clinic = $this->handleRegistration($data);

            $this->form->model($this->getModel())->saveRelationships();

        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction()
                ? $this->rollbackDatabaseTransaction()
                : $this->commitDatabaseTransaction();

            return;
        }

        $this->commitDatabaseTransaction();

        $this->sendRegisteredNotification();

        if ($redirectUrl = $this->getRedirectUrl()) {
            $this->redirectIntended($redirectUrl, FilamentView::hasSpaMode($redirectUrl));
        }
    }

    protected function sendRegisteredNotification()
    {
        return Notification::make()
            ->success()
            ->title('کلینیک جدید با موفقیت ثبت شد.')
            ->send();
    }

    public function getFormContentComponents(): Form
    {
        return Form::make([EmbeddedSchema::make($this->formId)])
            ->id($this->formId)
            ->livewireSubmitHandler($this->livewireSubmitFormMethodName)
            ->components($this->getFormComponents())
            ->footer(
                $this->getFormActionsContentComponents(),
            );
    }

    public function getFormActionsContentComponents(): Actions
    {
        return Actions::make($this->getFormActions())
            ->alignment($this->getFormActionsAlignment())
            ->fullWidth($this->hasFormActionsFullWidth)
            ->sticky($this->areFormActionsSticky());
    }

    /**
     * @return array<Actions | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getFormSubmitAction(),
            $this->getFormCancelAction(),
        ];
    }

    protected function getFormSubmitAction(): Action
    {
        return $this->getFormRegisterAction();
    }

    protected function getFormRegisterAction(): Action
    {
        return Action::make('submit')
            ->label('ثبت')
            ->submit($this->livewireSubmitFormMethodName);
    }

    protected function getFormCancelAction(): Action
    {
        return Action::make('cancel')
            ->label('لغو')
            ->color(Color::Red)
            ->url(url()->previous());
    }

    public function allFormComponents(Schema $schema): Schema
    {
        return $schema->components(
            $this->getFormComponents(),
        );
    }

    /**
     * @return array<Component>
     */
    protected function getFormComponents(): array
    {
        return [
            $this->getNameFormComponents(),
        ];
    }

    protected function getNameFormComponents()
    {
        return TextInput::make('name')
            ->label('نام کلینیک')
            ->required()
            ->string()
            ->maxLength(255);
    }

    protected function handleRegistration(array $data): Clinic
    {
        $clinic = $this->getModel()::create($data);

        $clinic->users()->attach(auth()->user());

        return $clinic;
    }
}
