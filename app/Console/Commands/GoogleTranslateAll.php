<?php

namespace App\Console\Commands;

use App\Models\SertifikatKompetensi;
use App\Models\SertifikatBahasaInternasional;
use App\Models\SertifikatMagang;
use App\Models\SertifikatPendidikanKarakter;
use App\Models\SertifikatPenghargaan;
use App\Models\SertifikatOrganisasi;
use App\Services\GoogleTranslateService;
use Illuminate\Console\Command;

class GoogleTranslateAll extends Command
{
    protected $signature = 'translate:google 
                            {--model=all : Model to translate (all, kompetensi, bahasa, magang, karakter, penghargaan, organisasi)}
                            {--force : Force re-translation even if already translated}';
    
    protected $description = 'Translate all certificates using Google Translate';
    
    protected $translator;
    
    public function __construct(GoogleTranslateService $translator)
    {
        parent::__construct();
        $this->translator = $translator;
    }
    
    public function handle()
    {
        $this->info('🔤 Starting Google Translate Batch Process...');
        
        // Test connection first
        $test = $this->translator->testConnection();
        
        if (!$test['success']) {
            $this->warn('⚠️  Google Translate may not be accessible.');
            $this->warn('Error: ' . $test['error']);
            
            if (!$this->confirm('Continue with fallback glossary?', false)) {
                $this->error('Aborted.');
                return;
            }
        }
        
        $model = $this->option('model');
        $force = $this->option('force');
        
        if ($model === 'all') {
            $this->translateAllModels($force);
        } else {
            $this->translateSingleModel($model, $force);
        }
        
        $this->info('✅ Translation completed!');
    }
    
    private function translateAllModels(bool $force = false)
    {
        $models = [
            'kompetensi' => SertifikatKompetensi::class,
            'bahasa' => SertifikatBahasaInternasional::class,
            'magang' => SertifikatMagang::class,
            'karakter' => SertifikatPendidikanKarakter::class,
            'penghargaan' => SertifikatPenghargaan::class,
            'organisasi' => SertifikatOrganisasi::class,
        ];
        
        $total = 0;
        
        foreach ($models as $name => $modelClass) {
            $count = $this->processModel($modelClass, $force, $name);
            $this->info("✓ {$count} certificates translated from {$name}");
            $total += $count;
        }
        
        $this->info("\n📊 Total: {$total} certificates translated");
    }
    
    private function translateSingleModel(string $modelName, bool $force = false)
    {
        $modelMap = [
            'kompetensi' => SertifikatKompetensi::class,
            'bahasa' => SertifikatBahasaInternasional::class,
            'magang' => SertifikatMagang::class,
            'karakter' => SertifikatPendidikanKarakter::class,
            'penghargaan' => SertifikatPenghargaan::class,
            'organisasi' => SertifikatOrganisasi::class,
        ];
        
        if (!isset($modelMap[$modelName])) {
            $this->error("Invalid model: {$modelName}");
            return;
        }
        
        $count = $this->processModel($modelMap[$modelName], $force, $modelName);
        $this->info("✓ {$count} certificates translated from {$modelName}");
    }
    
    private function processModel(string $modelClass, bool $force, string $modelName): int
    {
        $query = $modelClass::query();
        
        if (!$force) {
            // Only translate untranslated
            $query->where(function($q) {
                $q->whereNull('nama_inggris')
                  ->orWhere('nama_inggris', '')
                  ->orWhere('auto_translated', false);
            });
        }
        
        $certificates = $query->get();
        $count = $certificates->count();
        
        if ($count === 0) {
            $this->info("No certificates to translate for {$modelName}");
            return 0;
        }
        
        $this->info("Translating {$count} {$modelName} certificates...");
        
        $bar = $this->output->createProgressBar($count);
        
        $translatedCount = 0;
        
        foreach ($certificates as $cert) {
            try {
                $translated = $this->translator->translateCertificate($cert->nama_sertifikat);
                
                if ($translated && $translated !== $cert->nama_sertifikat) {
                    $cert->nama_inggris = $translated;
                    $cert->auto_translated = true;
                    $cert->save();
                    $translatedCount++;
                }
                
                // Small delay to avoid rate limiting
                usleep(100000); // 0.1 second
                
            } catch (\Exception $e) {
                $this->warn("Error translating {$cert->nama_sertifikat}: " . $e->getMessage());
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        
        return $translatedCount;
    }
}