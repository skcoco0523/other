<?php

//namespace App\Helpers;

// app/Helpers/Mail_send.php
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;
use Illuminate\Support\HtmlString;
use App\Jobs\SendMailJob;

class MailContent extends Mailable
{
    //protected $mailMessage;

    public function __construct($mailMessage)
    {
        $this->view(['html' => $mailMessage->render()])
        ->subject($mailMessage->subject);
    }

}

//メール送信関数　テンプレート内データ,送信先
if (! function_exists('mail_send')) {
    function mail_send($send_info, $mail, $tmpl){
        $error_log = __FUNCTION__.".log";
        make_error_log($error_log,"mail=".$mail."  tmpl=".$tmpl);
        $mailMessage = get_MailMessage($send_info, $tmpl);

        if ($mailMessage) {
            $mailable = new MailContent($mailMessage);
            //dd($mailable);
            //即時実行
            //Mail::to($mail)->send($mailable);

            // SendMailJobをディスパッチしてバックグラウンドで実行
            SendMailJob::dispatch($mail,$mailable);
        }else{
            make_error_log($error_log,"not_tmpl");
        }
    }
}

//テンプレートからメッセージ取得
/*  使用例
    $send_info = new \stdClass();
    $send_info->user_name = $request->name;
    $send_info->now_user_cnt = User::count();
    $mail = "syunsuke.05.23.15@gmail.com";//送信先
    $tmpl='user_reg_notice';//  送信内容
    mail_send($send_info, $mail, $tmpl);
*/
if (! function_exists('get_MailMessage')) {
    function get_MailMessage($send_info, $tmpl)
    {
        $error_log = __FUNCTION__.".log";
        //other\vendor\laravel\framework\src\Illuminate\Notifications\Messages\MailMessage.php
        switch($tmpl){
            case 'password_reset':
                $MailMessage = (new MailMessage)
                    ->markdown('emails.mail')
                    ->subject(Lang::get('パスワードリセット'))
                    ->line(Lang::get('本メールはパスワードリセットのご案内です。'))
                    ->action(Lang::get('リセットはこちらから'), url(config('app.url').route('password.reset', ['token' => $send_info->token, 'email' => $send_info->mail], false)))
                    ->line(Lang::get('このパスワード リセット リンクは :count 分後に期限切れになります。', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
                    ->line(Lang::get('もしパスワード再発行をリクエストしていない場合、操作は不要です。'))
                    ->line(Lang::get('************************************'))
                    ->line(Lang::get('問い合わせ先：skcoco.05.23@gmail.com'))
                    ->line(Lang::get('************************************'));

                return $MailMessage;
                break;
                    
            case 'user_reg':
                $MailMessage = (new MailMessage)
                ->markdown('emails.mail')
                ->subject(Lang::get('会員登録のお知らせ'))
                ->line(Lang::get($send_info->name.'様'))
                ->line(Lang::get('この度は会員のご登録ありがとうございます。'))
                ->line(Lang::get('本メールは登録された時点で送信される自動配信メールです。'))
                ->line(Lang::get('メールが不要の場合は、配信停止設定をお願いいたします。'))
                ->action(Lang::get('配信設定はこちらから'), url(route('profile-show')))
                ->line(Lang::get('************************************'))
                ->line(Lang::get('問い合わせ先：skcoco.05.23@gmail.com'))
                ->line(Lang::get('************************************'));

                return $MailMessage;
                break;


            //ここからは管理者充て
            case 'user_reg_notice':
                $MailMessage = (new MailMessage)
                ->markdown('emails.mail')
                ->subject(Lang::get('【その他:管理者】ユーザー登録通知'))
                ->line(Lang::get('新規登録者名：'. $send_info->user_name))
                ->line(Lang::get('現在ユーザー数：'. $send_info->now_user_cnt));

                return $MailMessage;
                break;

            default:
                return null;
        }
    }
}
