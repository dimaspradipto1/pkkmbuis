<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluasiMenu extends Model
{
    use HasFactory;

    public const EXCLUDED_ROUTES = [
        'evaluasipengenalanwawasanibnusina.index',
        'evaluasikewirausahaan.index',
        'evaluasipencarianbakatmahasiswa.index',
        'evaluasimotivasiwalikotabatam.index',
    ];

    public const MODEL_MAP_BY_ROUTE = [
        'evaluasipelayanankemahasiswaanpusatprestasi.index' => \App\Models\EvaluasiPelayananKemahasiswaanPusatPrestasi::class,
        'evaluasipelayanansistemakademik.index' => \App\Models\EvaluasiPelayanansistemAkademik::class,
        'evaluasipelayanansistemadministrasikeuangan.index' => \App\Models\EvaluasiPelayanansistemAdministrasiKeuangan::class,
        'evaluasikehidupanberbangsabelanegara.index' => \App\Models\EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegara::class,
        'evaluasisistempendidikantinggidiindonesia.index' => \App\Models\EvaluasiSistemPendidikanTinggidiIndonesia::class,
        'evaluasipendidikantinggieradigital.index' => \App\Models\EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustri::class,
        'evaluasipengenalank3l.index' => \App\Models\EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkungan::class,
        'evaluasiperpustakaan.index' => \App\Models\Perpustakaan::class,
        'evaluasiikauis.index' => \App\Models\EvaluasiIkaUis::class,
        'evaluasimotivasigubernurkepulauanriau.index' => \App\Models\EvaluasiMotivasiGubernurKepulauanRiau::class,
        'evaluasifikes.index' => \App\Models\EvaluasiFikes::class,
        'evaluasifst.index' => \App\Models\EvaluasiFst::class,
        'evaluasifeb.index' => \App\Models\EvaluasiFeb::class,
    ];

    public const MODEL_MAP_BY_NOMOR = [
        1  => \App\Models\EvaluasiPengenalanWawasanIbnuSina::class,
        2  => \App\Models\EvaluasiPelayananKemahasiswaanPusatPrestasi::class,
        3  => \App\Models\EvaluasiPelayanansistemAkademik::class,
        4  => \App\Models\EvaluasiPelayanansistemAdministrasiKeuangan::class,
        5  => \App\Models\EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegara::class,
        6  => \App\Models\EvaluasiSistemPendidikanTinggidiIndonesia::class,
        7  => \App\Models\EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustri::class,
        8  => \App\Models\EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkungan::class,
        9  => \App\Models\Perpustakaan::class,
        10 => \App\Models\EvaluasiIkaUis::class,
        11 => \App\Models\EvaluasiKewirausahaan::class,
        12 => \App\Models\EvaluasiPencarianBakatMahasiswa::class,
        13 => \App\Models\EvaluasiMotivasiWaliKotaBatam::class,
        14 => \App\Models\EvaluasiMotivasiGubernurKepulauanRiau::class,
        15 => \App\Models\EvaluasiFikes::class,
        16 => \App\Models\EvaluasiFst::class,
        17 => \App\Models\EvaluasiFeb::class,
    ];

    protected $fillable = [
        'nomor',
        'nama',
        'route_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeAvailable($query)
    {
        return $query->whereNotIn('route_name', self::EXCLUDED_ROUTES);
    }

    public function getModelClassAttribute(): ?string
    {
        return self::MODEL_MAP_BY_ROUTE[$this->route_name] ?? self::MODEL_MAP_BY_NOMOR[$this->nomor] ?? null;
    }

    public const FACULTY_ROUTES = [
        'evaluasifikes.index' => 'FIKES',
        'evaluasifst.index'   => 'FST',
        'evaluasifeb.index'   => 'FEB',
    ];

    public function getFacultyCodeAttribute(): ?string
    {
        return self::FACULTY_ROUTES[$this->route_name] ?? null;
    }

    public function isFacultyMenu(): bool
    {
        return isset(self::FACULTY_ROUTES[$this->route_name]);
    }

    public function matchesUserFaculty(?User $user): bool
    {
        if (!$this->isFacultyMenu()) {
            return true;
        }

        if (!$user) {
            return true;
        }

        if ($user->role !== 'mahasiswa') {
            return true;
        }

        $userFacultyCode = $user->faculty_code;
        if ($userFacultyCode) {
            return $this->faculty_code === $userFacultyCode;
        }

        $fak = strtoupper($user->fakultas ?? '');
        if ($this->faculty_code === 'FIKES' && (str_contains($fak, 'FIKES') || str_contains($fak, 'KESEHATAN'))) {
            return true;
        }
        if ($this->faculty_code === 'FST' && (str_contains($fak, 'FST') || (str_contains($fak, 'SAINS') && str_contains($fak, 'TEKNOLOGI')))) {
            return true;
        }
        if ($this->faculty_code === 'FEB' && (str_contains($fak, 'FEB') || (str_contains($fak, 'EKONOMI') && str_contains($fak, 'BISNIS')))) {
            return true;
        }

        return false;
    }

    public static function getMenusForUser(?User $user, bool $onlyActive = false)
    {
        $query = self::available()->orderBy('nomor');
        if ($onlyActive) {
            $query->where('is_active', true);
        }
        $menus = $query->get();

        if (!$user || $user->role !== 'mahasiswa') {
            return $menus;
        }

        return $menus->filter(fn($menu) => $menu->matchesUserFaculty($user))->values();
    }

    public function getCleanNamaAttribute(): string
    {
        return preg_replace('/^\d+\.\s*/', '', $this->nama);
    }
}
