<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $url = config('app.frontend_url') . "/reset-password/$token?email={$notifiable->getEmailForPasswordReset()}";

            return (new MailMessage)
                ->subject('Recuperar Cuenta')
                ->greeting('¡Hola ' . $notifiable->name . '!')
                ->line('Está recibiendo este correo electrónico porque recibimos una solicitud de restablecimiento de contraseña para su cuenta.')
                ->action('Reestablecer Contraseña', $url)
                ->line('Este enlace para restablecer la contraseña caducará en 60 minutos.')
                ->line('Si no solicitó un restablecimiento de contraseña, no es necesario realizar ninguna otra acción.');
        });

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            // $url = str_replace(url('/'), config('app.frontend_url'), $url);

            return (new MailMessage)
                ->subject('Verificar Cuenta')
                ->greeting('¡Hola ' . $notifiable->name . '!')
                ->line('Tu Cuenta ya Casi Esta Lista.')
                ->line('Haga clic en el botón a continuación para verificar su dirección de correo electrónico.')
                ->action('Confirmar Cuenta', $url)
                ->line('Si no creó una cuenta, no es necesario realizar ninguna otra acción.')
                ->line('¡DevPoints te da la bienvenida a la familia!');
        });
    }
}
