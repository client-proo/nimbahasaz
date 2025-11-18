<?php
require_once __DIR__ . '/vendor/autoload.php';

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Client;

// =========================
// تنظیمات
// =========================
$token = getenv('BOT_TOKEN') ?: '8367127956:AAHAR6zf2m4_hNJOw4cesM_3ExsNacvWxUU';
define('TOKEN', $token);
define('ANTI_SPAM_TIME', 120);

// =========================
// دیتابیس‌ها
// =========================
$FILE_DB = [];
$USER_ACCESS = [];
$SENT_FILES = [];
$LAST_SEND = [];

// =========================
// توابع کمکی
// =========================
function format_remaining($seconds) {
    $seconds = intval($seconds);
    if ($seconds <= 0) return "منقضی شده!";
    
    $parts = [];
    $h = floor($seconds / 3600);
    $m = floor(($seconds % 3600) / 60);
    $s = $seconds % 60;
    
    if ($h) $parts[] = "{$h} ساعت";
    if ($m) $parts[] = "{$m} دقیقه";
    if ($s) $parts[] = "{$s} ثانیه";
    
    if (count($parts) == 1) return $parts[0] . " باقی مونده";
    if (count($parts) == 2) return $parts[0] . " و " . $parts[1] . " باقی مونده";
    return $parts[0] . " و " . $parts[1] . " و " . $parts[2] . " باقی مونده";
}

function to_shamsi($timestamp) {
    try {
        $date = new DateTime();
        $date->setTimestamp($timestamp);
        $persian_date = \Morilog\Jalali\Jalalian::fromDateTime($date);
        return $persian_date->toString('Y/m/d - H:i:s');
    } catch (Exception $e) {
        return date('Y/m/d - H:i:s', $timestamp);
    }
}

function generate_code() {
    return substr(md5(uniqid() . rand(1000, 9999)), 0, 20);
}

function auto_cleanup($bot) {
    global $FILE_DB, $USER_ACCESS, $SENT_FILES, $LAST_SEND;
    
    $now = time();
    $expired = [];
    
    foreach ($FILE_DB as $code => $data) {
        if ($now > $data['expire']) {
            $expired[] = $code;
        }
    }
    
    foreach ($expired as $code) {
        $data = $FILE_DB[$code];
        
        try {
            $bot->deleteMessage($data['chat_id'], $data['msg_id']);
        } catch (Exception $e) {}
        
        foreach ($data['sent'] as $sent_msg) {
            try {
                $bot->deleteMessage($sent_msg['chat_id'], $sent_msg['msg_id']);
            } catch (Exception $e) {}
        }
        
        try {
            $bot->sendMessage($data['chat_id'], "فایل شما منقضی شد. لطفاً دوباره ارسال کنید.");
        } catch (Exception $e) {}
        
        unset($USER_ACCESS[$code]);
        unset($FILE_DB[$code]);
        
        foreach ($SENT_FILES as $user_id => &$files) {
            if (($key = array_search($code, $files)) !== false) {
                unset($files[$key]);
            }
        }
        
        foreach ($LAST_SEND as $user_id => $last_time) {
            if (isset($SENT_FILES[$user_id]) && empty($SENT_FILES[$user_id])) {
                unset($LAST_SEND[$user_id]);
            }
        }
    }
}

// =========================
// ایجاد ربات
// =========================
$bot = new Client(TOKEN);

// =========================
// مدیریت callback
// =========================
$bot->callbackQuery(function($callback) use (&$FILE_DB, &$USER_ACCESS, $bot) {
    $code = $callback->getData();
    $user_id = $callback->getFrom()->getId();
    $message = $callback->getMessage();
    
    if (!isset($FILE_DB[$code])) {
        $bot->answerCallbackQuery($callback->getId(), "لینک منقضی یا اشتباه!", true);
        return;
    }
    
    $data = $FILE_DB[$code];
    
    if (time() > $data['expire']) {
        $bot->answerCallbackQuery($callback->getId(), "لینک منقضی شد!", true);
        return;
    }
    
    if (!isset($USER_ACCESS[$code])) {
        $USER_ACCESS[$code] = [];
    }
    
    if (isset($USER_ACCESS[$code][$user_id]) && (time() - $USER_ACCESS[$code][$user_id] < 6 * 3600)) {
        $warn = $bot->sendMessage($message->getChat()->getId(), 
            "امکان دریافت فایل تکراری بیش از یکبار در هر 6 ساعت وجود ندارد.");
        
        $FILE_DB[$code]['sent'][] = [
            'chat_id' => $warn->getChat()->getId(),
            'msg_id' => $warn->getMessageId()
        ];
        
        $bot->answerCallbackQuery($callback->getId(), "امکان دریافت تکراری نیست!", true);
        return;
    }
    
    $USER_ACCESS[$code][$user_id] = time();
    $remaining = format_remaining($data['expire'] - time());
    
    try {
        switch ($data['ftype']) {
            case 'photo':
                $msg = $bot->sendPhoto($message->getChat()->getId(), $data['file_id']);
                break;
            case 'video':
                $msg = $bot->sendVideo($message->getChat()->getId(), $data['file_id']);
                break;
            case 'audio':
                $msg = $bot->sendAudio($message->getChat()->getId(), $data['file_id']);
                break;
            default:
                $msg = $bot->sendDocument($message->getChat()->getId(), $data['file_id']);
                break;
        }
    } catch (Exception $e) {
        $bot->answerCallbackQuery($callback->getId(), "خطا در ارسال فایل!", true);
        return;
    }
    
    $info = $bot->sendMessage($msg->getChat()->getId(), 
        "تاریخ انقضا:\n`" . to_shamsi($data['expire']) . "`\n" . $remaining, 
        "Markdown");
    
    $FILE_DB[$code]['sent'][] = [
        'chat_id' => $msg->getChat()->getId(),
        'msg_id' => $msg->getMessageId()
    ];
    $FILE_DB[$code]['sent'][] = [
        'chat_id' => $info->getChat()->getId(),
        'msg_id' => $info->getMessageId()
    ];
    
    $bot->answerCallbackQuery($callback->getId(), "فایل ارسال شد!");
});

// =========================
// دستور start
// =========================
$bot->command('start', function($message) use (&$FILE_DB, $bot) {
    $args = explode(' ', $message->getText());
    
    if (count($args) < 2 || !str_starts_with($args[1], 'file_')) {
        $bot->sendMessage($message->getChat()->getId(),
            "🤖 LinkBolt Pro\n\n📁 فایل بفرست → لینک ۶۰ ثانیه‌ای با دکمه شیشه‌ای\n" .
            "⚡ همه می‌تونن بگیرن\n🔥 بعد ۱ دقیقه می‌سوزه!\n\n🔄 بفرست و کپی کن!",
            "Markdown", true);
        return;
    }
    
    $code = substr($args[1], 5);
    
    if (!isset($FILE_DB[$code]) || time() > $FILE_DB[$code]['expire']) {
        $bot->sendMessage($message->getChat()->getId(), "❌ لینک منقضی شد!");
        return;
    }
    
    $expire = $FILE_DB[$code]['expire'];
    $keyboard = new InlineKeyboardMarkup([
        [['text' => '📥 دریافت فایل', 'callback_data' => $code]]
    ]);
    
    $bot->sendMessage($message->getChat()->getId(),
        "✅ لینک آماده است!\n\n📅 تاریخ انقضا:\n`" . to_shamsi($expire) . "`\n" .
        format_remaining($expire - time()) . " باقی مونده\n\n🎯 دکمه زیر رو بزن!",
        "Markdown", false, null, $keyboard);
});

// =========================
// مدیریت فایل‌ها
// =========================
$bot->on(function($update) use (&$FILE_DB, &$SENT_FILES, &$LAST_SEND, $bot) {
    $message = $update->getMessage();
    if (!$message) return;
    
    $user_id = $message->getFrom()->getId();
    $now = time();
    
    auto_cleanup($bot);
    
    if (isset($LAST_SEND[$user_id]) && ($now - $LAST_SEND[$user_id] < ANTI_SPAM_TIME)) {
        $remaining = ANTI_SPAM_TIME - ($now - $LAST_SEND[$user_id]);
        $m = floor($remaining / 60);
        $s = $remaining % 60;
        $countdown = $m ? "{$m} دقیقه و {$s} ثانیه" : "{$s} ثانیه";
        
        $bot->sendMessage($message->getChat()->getId(), 
            "⏰ از اسپم کردن خودداری کنید!\nزمان باقی‌مانده تا ارسال بعدی: {$countdown}");
        return;
    }
    
    $file_id = null;
    $ftype = null;
    $name = null;
    
    if ($message->getPhoto()) {
        $photos = $message->getPhoto();
        $file_id = end($photos)->getFileId();
        $ftype = 'photo';
        $name = 'عکس.jpg';
    } elseif ($message->getVideo()) {
        $video = $message->getVideo();
        $file_id = $video->getFileId();
        $ftype = 'video';
        $name = $video->getFileName() ?: 'ویدیو.mp4';
    } elseif ($message->getDocument()) {
        $document = $message->getDocument();
        $file_id = $document->getFileId();
        $ftype = 'document';
        $name = $document->getFileName() ?: 'فایل';
    } elseif ($message->getAudio()) {
        $audio = $message->getAudio();
        $file_id = $audio->getFileId();
        $ftype = 'audio';
        $name = $audio->getFileName() ?: 'آهنگ.mp3';
    } else {
        return;
    }
    
    $active_files = $SENT_FILES[$user_id] ?? [];
    foreach ($FILE_DB as $code => $data) {
        if ($data['file_id'] === $file_id && in_array($code, $active_files)) {
            $bot->sendMessage($message->getChat()->getId(), "⚠️ فایل تکراری است.");
            return;
        }
    }
    
    $code = generate_code();
    $expire = $now + 60;
    $bot_username = $bot->getMe()->getUsername();
    $link = "https://t.me/{$bot_username}?start=file_{$code}";
    
    $keyboard = new InlineKeyboardMarkup([
        [['text' => '📥 دریافت فایل', 'callback_data' => $code]]
    ]);
    
    $bot->sendMessage($message->getChat()->getId(),
        "🎉 لینک ۶۰ ثانیه‌ای آماده شد!\n\n📄 **نام:** `{$name}`\n⏰ **انقضا:** `" . to_shamsi($expire) . "`\n" .
        format_remaining($expire - $now) . " باقی مونده\n\n🔗 `{$link}`\n\n📋 کپی کن و بفرست!",
        "Markdown", true, null, $keyboard);
    
    $FILE_DB[$code] = [
        'file_id' => $file_id,
        'expire' => $expire,
        'ftype' => $ftype,
        'chat_id' => $message->getChat()->getId(),
        'msg_id' => $message->getMessageId() + 1,
        'sent' => []
    ];
    
    $SENT_FILES[$user_id][] = $code;
    $LAST_SEND[$user_id] = $now;
    
}, function($update) {
    $message = $update->getMessage();
    return $message && (
        $message->getPhoto() || 
        $message->getVideo() || 
        $message->getDocument() || 
        $message->getAudio()
    );
});

// =========================
// اجرای ربات
// =========================
echo "🚀 LinkBolt Pro روشن شد! | ⏰ زمان هوشمند فعال | 🛡️ ضد اسپم فعال\n";

try {
    $bot->run();
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    sleep(10);
}
