<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectUnit;
use App\Models\Cashflow;
use App\Models\ProjectDocument;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::where('email', 'bod@rbktracker.com')->first();
        $pengawasUser = User::where('email', 'pengawas@rbktracker.com')->first();

        // -------------------------------------------------------------
        // PROJECT 1: Grand RBK Residence (RBK Land, On Progress)
        // -------------------------------------------------------------
        $p1 = Project::create([
            'name' => 'Perumahan Grand RBK Residence',
            'category' => 'rbk_land',
            'status' => 'on_progress',
            'planned_progress' => 65.00,
            'actual_progress' => 68.50,
            'total_contract_value' => 12500000000, // Rp 12.5 M
        ]);

        // Units for Project 1
        foreach (range(1, 6) as $num) {
            ProjectUnit::create([
                'project_id' => $p1->id,
                'block_name' => 'Blok A',
                'unit_number' => 'No. ' . sprintf('%02d', $num),
            ]);
        }
        foreach (range(1, 4) as $num) {
            ProjectUnit::create([
                'project_id' => $p1->id,
                'block_name' => 'Blok B',
                'unit_number' => 'No. ' . sprintf('%02d', $num),
            ]);
        }

        // Cashflows for Project 1
        Cashflow::create([
            'project_id' => $p1->id,
            'type' => 'income',
            'category' => 'others',
            'amount' => 4500000000,
            'transaction_date' => '2026-01-15',
            'notes' => 'Termin I Pembayaran Konsumen Blok A',
        ]);
        Cashflow::create([
            'project_id' => $p1->id,
            'type' => 'income',
            'category' => 'others',
            'amount' => 3800000000,
            'transaction_date' => '2026-03-20',
            'notes' => 'Pencairan Bank KPR Tahap 1',
        ]);
        Cashflow::create([
            'project_id' => $p1->id,
            'type' => 'expense',
            'category' => 'material',
            'amount' => 2100000000,
            'transaction_date' => '2026-02-10',
            'notes' => 'Pembelian Semen, Besi Beton & Bata Ringan',
        ]);
        Cashflow::create([
            'project_id' => $p1->id,
            'type' => 'expense',
            'category' => 'labor',
            'amount' => 1450000000,
            'transaction_date' => '2026-02-28',
            'notes' => 'Upah Mandor & Tukang Struktur Tahap I',
        ]);
        Cashflow::create([
            'project_id' => $p1->id,
            'type' => 'expense',
            'category' => 'operational',
            'amount' => 480000000,
            'transaction_date' => '2026-03-10',
            'notes' => 'Perizinan PBG & Biaya Listrik Kerja Site',
        ]);
        Cashflow::create([
            'project_id' => $p1->id,
            'type' => 'expense',
            'category' => 'others',
            'amount' => 150000000,
            'transaction_date' => '2026-04-05',
            'notes' => 'Sewa Alat Berat Excravator 1 Bulan',
        ]);

        // Documents for Project 1
        ProjectDocument::create([
            'project_id' => $p1->id,
            'type' => 'rab',
            'title' => 'Rencana Anggaran Biaya (RAB) Grand RBK Final',
            'file_path' => 'documents/sample_rab_p1.pdf',
            'status' => 'approved',
            'uploaded_by' => $adminUser->id,
        ]);
        ProjectDocument::create([
            'project_id' => $p1->id,
            'type' => 'ded',
            'title' => 'Detail Engineering Design (DED) Siteplan & Arsitektur',
            'file_path' => 'documents/sample_ded_p1.pdf',
            'status' => 'approved',
            'uploaded_by' => $adminUser->id,
        ]);
        ProjectDocument::create([
            'project_id' => $p1->id,
            'type' => 'kurva_s',
            'title' => 'Kurva S Progres Mingguan - Revisi Q2',
            'file_path' => 'documents/sample_kurvas_p1.pdf',
            'status' => 'approved',
            'uploaded_by' => $pengawasUser->id,
        ]);
        ProjectDocument::create([
            'project_id' => $p1->id,
            'type' => 'invoice',
            'title' => 'Invoice Tagihan Material Semen & Beton PT Varia',
            'file_path' => 'documents/sample_invoice_p1.pdf',
            'status' => 'pending_payment',
            'uploaded_by' => $pengawasUser->id,
        ]);
        ProjectDocument::create([
            'project_id' => $p1->id,
            'type' => 'pks',
            'title' => 'Perjanjian Kerja Sama (PKS) Kontraktor Utama',
            'file_path' => 'documents/sample_pks_p1.pdf',
            'status' => 'paid',
            'uploaded_by' => $adminUser->id,
        ]);

        // -------------------------------------------------------------
        // PROJECT 2: Kavling RBK Commercial Hub (RBK Land, On Progress)
        // -------------------------------------------------------------
        $p2 = Project::create([
            'name' => 'Kavling RBK Commercial Hub',
            'category' => 'rbk_land',
            'status' => 'on_progress',
            'planned_progress' => 45.00,
            'actual_progress' => 38.00, // Deficit -7.00%
            'total_contract_value' => 8200000000, // Rp 8.2 M
        ]);

        foreach (range(1, 5) as $num) {
            ProjectUnit::create([
                'project_id' => $p2->id,
                'block_name' => 'Ruko Blok C',
                'unit_number' => 'Kav. ' . $num,
            ]);
        }

        Cashflow::create([
            'project_id' => $p2->id,
            'type' => 'income',
            'category' => 'others',
            'amount' => 3000000000,
            'transaction_date' => '2026-02-01',
            'notes' => 'Uang Muka Booking Ruko 5 Unit',
        ]);
        Cashflow::create([
            'project_id' => $p2->id,
            'type' => 'expense',
            'category' => 'material',
            'amount' => 1200000000,
            'transaction_date' => '2026-02-15',
            'notes' => 'Pekerjaan Urugan Tanah & Pemancangan Tiang Pancang',
        ]);
        Cashflow::create([
            'project_id' => $p2->id,
            'type' => 'expense',
            'category' => 'labor',
            'amount' => 650000000,
            'transaction_date' => '2026-03-01',
            'notes' => 'Upah Subkon Pondasi & Drainage',
        ]);

        ProjectDocument::create([
            'project_id' => $p2->id,
            'type' => 'rab',
            'title' => 'RAB Komersial Hub Kavling Ruko',
            'file_path' => 'documents/sample_rab_p2.pdf',
            'status' => 'approved',
            'uploaded_by' => $adminUser->id,
        ]);
        ProjectDocument::create([
            'project_id' => $p2->id,
            'type' => 'ppjb',
            'title' => 'Draf PPJB Ruko Blok C1 - C5',
            'file_path' => 'documents/sample_ppjb_p2.pdf',
            'status' => 'approved',
            'uploaded_by' => $adminUser->id,
        ]);

        // -------------------------------------------------------------
        // PROJECT 3: Perkantoran Non-RBK Prime (Non-RBK Land, Planning)
        // -------------------------------------------------------------
        $p3 = Project::create([
            'name' => 'Perkantoran Non-RBK Prime Tower',
            'category' => 'non_rbk_land',
            'status' => 'planning',
            'planned_progress' => 15.00,
            'actual_progress' => 15.00,
            'total_contract_value' => 15000000000, // Rp 15 M
        ]);

        ProjectUnit::create([
            'project_id' => $p3->id,
            'block_name' => 'Tower A',
            'unit_number' => 'Lantai 1-5',
        ]);

        Cashflow::create([
            'project_id' => $p3->id,
            'type' => 'income',
            'category' => 'others',
            'amount' => 2000000000,
            'transaction_date' => '2026-03-01',
            'notes' => 'DP Kontrak Kerjasama Pengembang Client',
        ]);
        Cashflow::create([
            'project_id' => $p3->id,
            'type' => 'expense',
            'category' => 'operational',
            'amount' => 350000000,
            'transaction_date' => '2026-03-15',
            'notes' => 'Studi Kelayakan Lingkungan (AMDAL) & Soil Test',
        ]);

        ProjectDocument::create([
            'project_id' => $p3->id,
            'type' => 'sp',
            'title' => 'Surat Perintah Kerja (SPK) Perencanaan Arsitektur',
            'file_path' => 'documents/sample_sp_p3.pdf',
            'status' => 'approved',
            'uploaded_by' => $adminUser->id,
        ]);

        // -------------------------------------------------------------
        // PROJECT 4: RBK Eco Smart Villa (RBK Land, Completed)
        // -------------------------------------------------------------
        $p4 = Project::create([
            'name' => 'RBK Eco Smart Villa',
            'category' => 'rbk_land',
            'status' => 'completed',
            'planned_progress' => 100.00,
            'actual_progress' => 100.00,
            'total_contract_value' => 18000000000, // Rp 18 M
        ]);

        foreach (['Villa Orchid', 'Villa Jasmine', 'Villa Lotus'] as $villa) {
            ProjectUnit::create([
                'project_id' => $p4->id,
                'block_name' => 'Zone Villa',
                'unit_number' => $villa,
            ]);
        }

        Cashflow::create([
            'project_id' => $p4->id,
            'type' => 'income',
            'category' => 'others',
            'amount' => 18000000000,
            'transaction_date' => '2026-01-10',
            'notes' => 'Pelunasan Pembayaran 100% dari Investor Eco Smart',
        ]);
        Cashflow::create([
            'project_id' => $p4->id,
            'type' => 'expense',
            'category' => 'material',
            'amount' => 9500000000,
            'transaction_date' => '2026-01-20',
            'notes' => 'Total Pembelian Material & Smart Home Appliances',
        ]);
        Cashflow::create([
            'project_id' => $p4->id,
            'type' => 'expense',
            'category' => 'labor',
            'amount' => 4200000000,
            'transaction_date' => '2026-02-15',
            'notes' => 'Total Upah Tenaga Kerja Ahli Villa Construction',
        ]);

        ProjectDocument::create([
            'project_id' => $p4->id,
            'type' => 'bast',
            'title' => 'Berita Acara Serah Terima (BAST) 100% Villa',
            'file_path' => 'documents/sample_bast_p4.pdf',
            'status' => 'approved',
            'uploaded_by' => $adminUser->id,
        ]);
        ProjectDocument::create([
            'project_id' => $p4->id,
            'type' => 'kwitansi',
            'title' => 'Kwitansi Pelunasan Rp 18 Miliar Final',
            'file_path' => 'documents/sample_kwitansi_p4.pdf',
            'status' => 'paid',
            'uploaded_by' => $adminUser->id,
        ]);
    }
}
