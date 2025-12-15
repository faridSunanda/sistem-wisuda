<?php

namespace App\Traits;

trait GoogleTranslateTrait
{
    /**
     * Auto-translate on save
     */
    protected static function bootGoogleTranslateTrait()
    {
        static::saving(function ($model) {
            $model->autoTranslateGoogle();
        });
    }
    
    /**
     * Auto-translate using Google Translate
     */
    public function autoTranslateGoogle()
    {
        // Only translate if nama_inggris is empty
        if (!empty($this->nama_sertifikat) && 
            (empty($this->nama_inggris) || $this->auto_translated)) {
            
            $translator = app(\App\Services\GoogleTranslateService::class);
            
            $translated = $translator->translateCertificate($this->nama_sertifikat);
            
            if ($translated && $translated !== $this->nama_sertifikat) {
                $this->nama_inggris = $translated;
                $this->auto_translated = true;
            }
        }
    }
    
    /**
     * Accessor for English name
     */
    public function getNamaInggrisAttribute($value)
    {
        // Lazy translate if empty
        if (empty($value) && !empty($this->nama_sertifikat)) {
            $translator = app(\App\Services\GoogleTranslateService::class);
            return $translator->translateCertificate($this->nama_sertifikat);
        }
        
        return $value;
    }
    
    /**
     * Manually trigger translation
     */
    public function translateNow(): self
    {
        if (!empty($this->nama_sertifikat)) {
            $translator = app(\App\Services\GoogleTranslateService::class);
            $this->nama_inggris = $translator->translateCertificate($this->nama_sertifikat);
            $this->auto_translated = true;
            $this->save();
        }
        
        return $this;
    }
}