<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getAttachmentsAttribute() {
        $attachments = Attachment::where('message_id', $this->id)->get();

        foreach( $attachments as $attachment ) {
            $mime = \Storage::disk('local')->getMimeType("tickets" . DIRECTORY_SEPARATOR . $attachment->path);

            if ($mime == "application/pdf") {
                $attachment->type = "img";
                $attachment->src = "/svg/pdf.png";
            } else if ( preg_match("/^image.+$/",$mime) ) {
                $attachment->type = "img";
                $attachment->src = route('ticket.file.download', $attachment->path);
            } else if ( preg_match("/^video.+$/",$mime) ) {
                $attachment->type = "video";
                $attachment->src = route('ticket.file.download', $attachment->path);
            } else if ( preg_match("/.+officedocument.+$/",$mime) || "application/msword" ) {
                $attachment->type = "img";
                $attachment->src = "/svg/office.png";
            } else {
                $attachment->type = "img";
                $attachment->src = "/svg/file.png";
            }
        }
        return $attachments;
    }

}
