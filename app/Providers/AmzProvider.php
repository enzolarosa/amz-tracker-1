<?php

namespace App\Providers;

use App\Models\QueriesLogger;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Schema;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AmzProvider extends ServiceProvider
{
    public function register()
    {
        $this->booted(function () {
//            Cashier::calculateTaxes();

            $this->customizeResetPasswordEmail();
            $this->horizonConfiguration();
            $this->customizeVerifyEmail();
            $this->responseMacro();
            $this->rateLimiter();

            if (config('amz.logs.queries')) {
                $this->logQueries();
            }
        });

//        Cashier::ignoreMigrations();
    }

    public static function version(): string
    {
        return config('amz.version') . '-' . trim(exec('git --git-dir ' . base_path('.git') . ' log --pretty="%h" -n1 HEAD'));
    }

    protected function customizeVerifyEmail()
    {
//        VerifyEmail::toMailUsing(function ($notifiable, $url) {
//            $mail = new \App\Mail\VerifyEmail($notifiable->name, $url);
//            $mail->to($notifiable->email, $notifiable->name)
//                ->subject(__('Verify your account'))
//                ->onQueue('mailer');
//
//            return $mail;
//        });
//
//        VerifyEmail::createUrlUsing(function ($user) {
//            $code = Str::uuid();
//            if (Schema::hasTable('user_email_verification')) {
//                DB::table('user_email_verification')->insert([
//                    'user_id' => $user->id,
//                    'code' => $code,
//                    'created_at' => now(),
//                ]);
//            }
//
//            return sprintf(config('amz.user_email_verify_endpoint'), config('amz.frontend_url'), $code);
//        });
    }

    protected function customizeResetPasswordEmail()
    {
//        ResetPassword::toMailUsing(function ($notifiable, $token) {
//            $url = sprintf(config('amz.user_reset_password_endpoint'), config('amz.frontend_url'), $token);
//
//            $mail = new ResetPasswordMail($notifiable->name, $url);
//            $mail->to($notifiable->email, $notifiable->name)
//                ->subject(__('Reset your password'))
//                ->onQueue('mailer');
//
//            return $mail;
//        });
    }

    protected function logQueries()
    {
        DB::listen(function (QueryExecuted $queryExecuted) {
            $sql = $queryExecuted->sql;
            if (
                !Str::contains($sql,'queries_logger')
            ) {
                $bindings = $queryExecuted->bindings;
                $time = $queryExecuted->time;
                if ($time >= config('amz.logs.queries_timeout', 20)) {
                    QueriesLogger::log($sql, $bindings, $time);
                }
            }
        });
    }

    protected function horizonConfiguration()
    {
        $queue['logger'] = [
            'connection' => 'redis',
            'queue' => [
                'queries',
                'requests',
            ],
            'balance' => 'auto',
            'maxProcesses' => 1,
            'memory' => 128,
            'tries' => 1,
            'nice' => 0,
        ];

        $wait = [
            'redis:queries' => 600,
            'redis:requests' => 600,
        ];
        $queue = array_merge(config('horizon.defaults'), $queue);
        $wait = array_merge(config('horizon.waits'), $wait);

        // load file sequences queues
        config([
            'horizon.defaults' => $queue,
            'horizon.waits' => $wait,
        ]);
    }

    protected function rateLimiter()
    {
    }

    protected function responseMacro()
    {
        Response::macro('success', function ($data) {
            return response()->json([
                'status' => true,
                'time' => now()->toIso8601String(),
                'data' => $data,
                'error' => [],
            ]);
        });

        Response::macro('fails', function ($data, $code = ResponseAlias::HTTP_UNPROCESSABLE_ENTITY) {
            return response()->json([
                'status' => false,
                'time' => now()->toIso8601String(),
                'data' => [],
                'error' => $data,
            ], $code);
        });
    }
}
