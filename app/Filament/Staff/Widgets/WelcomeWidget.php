<?php

namespace App\Filament\Staff\Widgets;

use App\Enums\PanelRole;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\User;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class WelcomeWidget extends Widget
{
    protected string $view = 'filament.widgets.welcome-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 0;

    protected static ?string $heading = null;

    public ?Model $clinic {
        set => $this->clinic = $value;
        get => $this->clinic ?? null;
    }

    public ?string $userName {
        get => $this->userName ?? '';
        set => $this->userName = $value;
    }

    public string $greetingMessage {
        get => $this->greetingMessage ?? '';
        set => $this->greetingMessage = $value;
    }

    public string $todayDate {
        get => $this->todayDate ?? '';
        set => $this->todayDate = $value;
    }

    public string $currentTime {
        get => $this->currentTime ?? '';
        set => $this->currentTime = $value;
    }

    public int $totalAppointments {
        get => $this->totalAppointments ?? 0;
        set => $this->totalAppointments = $value;
    }

    public int $pendingCount {
        get => $this->pendingCount ?? 0;
        set => $this->pendingCount = $value;
    }

    public int $confirmedCount {
        get => $this->confirmedCount ?? 0;
        set => $this->confirmedCount = $value;
    }

    public int $checkedInCount {
        get => $this->checkedInCount ?? 0;
        set => $this->checkedInCount = $value;
    }

    public int $rejectedCount {
        get => $this->rejectedCount ?? 0;
        set => $this->rejectedCount = $value;
    }

    public int $completedCount {
        get => $this->completedCount ?? 0;
        set => $this->completedCount = $value;
    }

    public int $cancelledCount {
        get => $this->cancelledCount ?? 0;
        set => $this->cancelledCount = $value;
    }

    public int $noShowCount {
        get => $this->noShowCount ?? 0;
        set => $this->noShowCount = $value;
    }

    public int $freeSlots {
        get => $this->freeSlots ?? 0;
        set => $this->freeSlots = $value;
    }

    public int $reservedSlots {
        get => $this->reservedSlots ?? 0;
        set => $this->reservedSlots = $value;
    }

    public int $activeDoctors {
        get => $this->activeDoctors ?? 0;
        set => $this->activeDoctors = $value;
    }

    public bool $hasSchedule {
        get => $this->hasSchedule ?? false;
        set => $this->hasSchedule = $value;
    }

    public string $firstAppointmentTime {
        get => $this->firstAppointmentTime ?? '';
        set => $this->firstAppointmentTime = $value;
    }

    public string $lastAppointmentTime {
        get => $this->lastAppointmentTime ?? '';
        set => $this->lastAppointmentTime = $value;
    }

    public string $workingHoursDisplay {
        get => $this->workingHoursDisplay ?? '';
        set => $this->workingHoursDisplay = $value;
    }

    public string $currentClinic {
        get => $this->currentClinic ?? '';
        set => $this->currentClinic = $value;
    }

    public string $joinedClinics {
        get => $this->joinedClinics ?? '';
        set => $this->joinedClinics = $value;
    }

    public string $loggedInRole {
        get => $this->loggedInRole ?? '';
        set => $this->loggedInRole = $value;
    }

    public function mount(): void
    {
        $this->clinic = Filament::getTenant();

        $this->prepareGreeting();
        $this->prepareAppointmentStats();
        $this->prepareScheduleSummary();
        $this->prepareQuickInfo();
        $this->countActiveDoctors();
    }

    public static function canView(): bool
    {
        return true;
    }

    private function prepareAppointmentStats(): void
    {
        $today = Carbon::today();

        $stats = Appointment::query()
            ->where('clinic_id', $this->clinic->getKey())
            ->whereHas('slot', fn ($q) => $q->whereDate('date', $today))
            ->selectRaw("
                COUNT(*) AS total,
                SUM(status = 'pending')  AS pending,
                SUM(status = 'confirmed')  AS confirmed,
                SUM(status = 'checked_in')  AS checked_in,
                SUM(status = 'completed')  AS completed,
                SUM(status = 'cancelled')  AS cancelled,
                SUM(status = 'no_show'  )  AS no_show
            ")
            ->first();

        $this->totalAppointments = $stats->total ?? 0;
        $this->pendingCount = $stats->pending ?? 0;
        $this->confirmedCount = $stats->confirmed ?? 0;
        $this->checkedInCount = $stats->checked_in ?? 0;
        $this->completedCount = $stats->completed ?? 0;
        $this->cancelledCount = $stats->cancelled ?? 0;
        $this->noShowCount = $stats->no_show ?? 0;
    }

    private function prepareScheduleSummary(): void
    {
        if ($this->totalAppointments === 0) {
            return;
        }

        $today = Carbon::today();

        $schedule = Appointment::query()
            ->where('clinic_id', $this->clinic->getKey())
            ->join('slots', 'appointments.slot_id', '=', 'slots.id')
            ->whereDate('slots.date', $today)
            ->when($this->clinic->getKey(), fn ($q) => $q->where('appointments.clinic_id', $this->clinic->getKey()))
            ->selectRaw('MIN(slots.start_time) AS first_start, MAX(slots.end_time) AS last_end')
            ->first();

        if (blank($schedule?->first_start)) {
            return;
        }

        $this->hasSchedule = true;

        $firstTime = Carbon::parse($schedule->first_start);
        $lastTime = Carbon::parse($schedule->last_end);

        $this->firstAppointmentTime = $firstTime->format('H:i');
        $this->lastAppointmentTime = $lastTime->format('H:i');

        $duration = $firstTime->diff($lastTime);
        $hours = $duration->h;
        $minutes = $duration->i;

        $this->workingHoursDisplay = sprintf('%d:%02d', $hours, $minutes);
    }

    private function prepareQuickInfo(): void
    {
        $user = Auth::user();

        $this->currentClinic = $this->clinic?->name ?? __('widgets/dashboard.not_available');
        $this->joinedClinics = $user->clinics->first()->name ?? __('widgets/dashboard.not_available');
        $this->loggedInRole = $user?->roles()->first()->name->value ?? __('widgets/dashboard.not_available');
    }

    public function getStatsCards(): array
    {
        return [
            [
                'label' => __('widgets/dashboard.stats.total.label'),
                'value' => $this->totalAppointments,
                'description' => __('widgets/dashboard.stats.total.description'),
                'borderColor' => 'border-t-violet-500',
                'iconBg' => 'bg-violet-50 dark:bg-violet-500/10',
                'iconColor' => 'text-violet-500 dark:text-violet-400',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />',
            ],
            [
                'label' => __('widgets/dashboard.stats.pending.label'),
                'value' => $this->pendingCount,
                'description' => __('widgets/dashboard.stats.pending.description'),
                'borderColor' => 'border-t-amber-500',
                'iconBg' => 'bg-amber-50 dark:bg-amber-500/10',
                'iconColor' => 'text-amber-500 dark:text-amber-400',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />',
            ],
            [
                'label' => __('widgets/dashboard.stats.confirmed.label'),
                'value' => $this->confirmedCount,
                'description' => __('widgets/dashboard.stats.confirmed.description'),
                'borderColor' => 'border-t-sky-500',
                'iconBg' => 'bg-sky-50 dark:bg-sky-500/10',
                'iconColor' => 'text-sky-500 dark:text-sky-400',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
            ],
            [
                'label' => __('widgets/dashboard.stats.checked_in.label'),
                'value' => $this->checkedInCount,
                'description' => __('widgets/dashboard.stats.checked_in.description'),
                'borderColor' => 'border-t-sky-500',
                'iconBg' => 'bg-sky-50 dark:bg-sky-500/10',
                'iconColor' => 'text-sky-500 dark:text-sky-400',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
            ],
            [
                'label' => __('widgets/dashboard.stats.completed.label'),
                'value' => $this->completedCount,
                'description' => __('widgets/dashboard.stats.completed.description'),
                'borderColor' => 'border-t-emerald-500',
                'iconBg' => 'bg-emerald-50 dark:bg-emerald-500/10',
                'iconColor' => 'text-emerald-500 dark:text-emerald-400',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75" />',
            ],
            [
                'label' => __('widgets/dashboard.stats.cancelled.label'),
                'value' => $this->cancelledCount,
                'description' => __('widgets/dashboard.stats.cancelled.description'),
                'borderColor' => 'border-t-rose-500',
                'iconBg' => 'bg-rose-50 dark:bg-rose-500/10',
                'iconColor' => 'text-rose-500 dark:text-rose-400',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
            ],
            [
                'label' => __('widgets/dashboard.stats.no_show.label'),
                'value' => $this->noShowCount,
                'description' => __('widgets/dashboard.stats.no_show.description'),
                'borderColor' => 'border-t-slate-400',
                'iconBg' => 'bg-slate-100 dark:bg-slate-500/10',
                'iconColor' => 'text-slate-500 dark:text-slate-400',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />',
            ],
            [
                'label' => __('widgets/dashboard.stats.active_doctors.label'),
                'value' => $this->activeDoctors,
                'description' => __('widgets/dashboard.stats.active_doctors.description'),
                'borderColor' => 'border-t-slate-400',
                'iconBg' => 'bg-slate-100 dark:bg-slate-500/10',
                'iconColor' => 'text-slate-500 dark:text-slate-400',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />',
            ],
        ];
    }

    /**
     * Quick-info items consumed by the Blade view.
     *
     * @return array<int, array{label: string, value: string, iconBg: string, iconColor: string, svg: string}>
     */
    public function getQuickInfoItems(): array
    {
        return [
            [
                'label' => __('widgets/dashboard.quick_info.clinic'),
                'value' => $this->currentClinic,
                'iconBg' => 'bg-teal-50 dark:bg-teal-500/10',
                'iconColor' => 'text-teal-600 dark:text-teal-400',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />',
            ],
            [
                'label' => __('widgets/dashboard.quick_info.role'),
                'value' => $this->loggedInRole,
                'iconBg' => 'bg-amber-50 dark:bg-amber-500/10',
                'iconColor' => 'text-amber-600 dark:text-amber-400',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />',
            ],
            [
                'label' => __('widgets/dashboard.quick_info.tenant'),
                'value' => $this->joinedClinics,
                'iconBg' => 'bg-indigo-50 dark:bg-indigo-500/10',
                'iconColor' => 'text-indigo-600 dark:text-indigo-400',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />',
            ],
        ];
    }

    private function prepareGreeting(): void
    {
        $this->userName = Filament::auth()->user()?->name;

        $this->greetingMessage = __('widgets/dashboard.greeting');

        $this->todayDate = Jalalian::now()
            ->format('l، j F Y');

        $this->currentTime = Jalalian::now()->format('H:i');
    }

    private function countActiveDoctors(): void
    {
        $today = Carbon::today();
        $dayOfWeek = $today->dayOfWeek;

        $this->activeDoctors = User::query()
            ->whereHas('roles', fn ($q) => $q->where('name', PanelRole::Doctor))
            ->whereHas('schedules', function ($q) use ($today, $dayOfWeek) {
                $q->where('clinic_id', $this->clinic->getKey())
                    ->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today)
                    ->whereJsonContains('days_of_week', $dayOfWeek);
            })
            ->count();
    }

    public function getEmptyScheduleTitle(): string
    {
        return __('widgets/dashboard.empty_schedule.panel.staff.title');
    }

    public function getEmptyScheduleDescription(): string
    {
        return __('widgets/dashboard.empty_schedule.panel.staff.description');
    }
}
