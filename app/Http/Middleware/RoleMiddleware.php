<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = $request->user();
            $currentPanelId = Filament::getCurrentOrDefaultPanel()->getId();
            $userPanelId = $user->resolvePanelId();

            if ($currentPanelId !== $userPanelId) {
                $this->sendUnauthorizedNotification($currentPanelId);

                return redirect()->to(
                    Filament::getPanel($userPanelId)->getUrl(),
                );
            }
        }

        return $next($request);
    }


    protected function sendUnauthorizedNotification(string $panelId): void
    {
        Notification::make()
            ->warning()
            ->title('Unauthorized Panel Access')
            ->body("Your role does not grant access to the $panelId panel.")
            ->send();
    }
}
