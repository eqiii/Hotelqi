<?php

namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class HotelProfile extends Model
    {
        use HasFactory;

        protected $fillable = [
            'name',
            'description',
            'address',
            'phone',
            'email',
            'logo',
            'facebook',
            'instagram',
            'twitter',
            'google_maps_embed',
        ];

        // ==================== ACCESSORS ====================

        public function getLogoUrlAttribute(): string
        {
            if ($this->logo) {
                return asset('storage/' . $this->logo);
            }
            return asset('images/default-logo.png');
        }

    // ==================== STATIC HELPER ====================

        /**
         * Mendapatkan profil hotel (singleton pattern)
         */
        public static function getProfile(): ?self
        {
            try {
                return self::first();
            } catch (\Throwable $e) {
                return null;
            }
        }

        /**
         * Update atau create profil hotel
         */
        public static function updateOrCreateProfile(array $data): self
        {
            $profile = self::first();
            if ($profile) {
                $profile->update($data);
                return $profile;
            }
            return self::create($data);
        }
    }
