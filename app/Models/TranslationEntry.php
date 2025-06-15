<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranslationEntry extends Model
{
    protected $fillable =  ['translation_id','language_id','tag_id','locale','content'];

    public function tag(){
        return $this->belongsTo(Tag::class);
    }
        public function translation(){
        return $this->belongsTo(Translation::class);
    }
        public function language(){
        return $this->belongsTo(Language::class);
    }
}
