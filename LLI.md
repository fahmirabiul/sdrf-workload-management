# Low-Level Instructions (LLI): Factory & Seeder Optimization

## Objective
Optimize development database seeders and factories to generate realistic, localized (Indonesian IT/Campus context), and high-density transactional data for dashboard visual testing, without altering any existing master data structures.

## Targeted Files
- `database/factories/SoftwareRequestFactory.php`
- `database/factories/DevelopmentProjectFactory.php`
- `database/factories/ProjectHistoryLogFactory.php`
- `database/seeders/TransactionDataSeeder.php`

---

## Technical Specifications & Requirements

### 1. Factory Optimization (Indonesian IT Context)
- **Localization:** Force the Faker instance to use Indonesian locale by initializing it with `Faker\Factory::create('id_ID')` inside the factory definition.
- **Remove Lorem Ipsum:** Completely eliminate any generic latin/lorem-ipsum text generators (`$faker->paragraph`, `$faker->sentence`).
- **Contextual Arrays:** Use `$faker->randomElement([...])` populated with realistic Indonesian Higher Education IT contexts. 
  
  *Examples for Custom Titles & Descriptions:*
  - *Titles:* "Integrasi SSO Google Workspace", "Sinkronisasi Feeder Neo-PDDIKTI", "Modul Pembayaran KKN via VA Mandiri", "Optimasi Query KHS SIAKAD", "Pembaruan Alur Validasi Cuti Dosen".
  - *Business Impact:* "Mempercepat sinkronisasi data dari 3 hari menjadi 5 menit otomatis", "Mencegah kebocoran manipulasi nilai mahasiswa oleh pihak luar", "Menghilangkan antrean fisik mahasiswa di biro keuangan".

### 2. TransactionDataSeeder Refactoring (Hybrid Structure)
The seeder must follow a strict sequential execution order:

#### PHASE A: Keep Explicit Baseline Scenarios (DO NOT ALTER)
Preserve the explicit data points for the core portfolio narrative:
1. **Programmer 1 (Budi Setiawan):** Must have 1 large project in June 2026 (13 Pts) marked as `close_suspended`, 1 critical bug fix project in June (8 Pts) marked as `production` (causing a total active load of 21 Pts to test the Conflict Protocol), and 1 cloned piece project (5 Pts) in July 2026 marked as `waiting`.
2. **Programmer 2 (Siti Aminah):** Must have a stable project running from June to July 2026 (12 Pts) marked as `in_development`.
3. **Core Logs & Comments:** Keep the precise `project_comments` and `project_history_logs` attached to these specific baseline IDs.

#### PHASE B: High-Density Factory Automation
- After executing Phase A, use the optimized factories to generate **30 to 40 additional random records** across `software_requests` and `development_projects`.
- Distribute the created data across various months in the year **2026** (specifically from January to August 2026) to simulate historical timeline depth.

### 3. Automated History Log Generation Rules
To prevent empty history logs on finished tasks, any automated insertion (via factory loops or seeders) that assigns a project status must follow these cascading log generation rules:

- **If project_status = `uat_testing`:**
  Automatically create 1 log entry in `project_history_logs`:
  * `action_type`: `PROJECT_ASSIGNED` (waiting -> in_development).
- **If project_status = `ready_for_production`:**
  Automatically create 2 chronological log entries:
  * `PROJECT_ASSIGNED` (waiting -> in_development).
  * `UAT_APPROVED` (in_development -> ready_for_production).
- **If project_status = `production` or `closed`:**
  Automatically create 3 chronological log entries:
  * `PROJECT_ASSIGNED` (waiting -> in_development).
  * `UAT_APPROVED` (in_development -> ready_for_production).
  * `DEPLOYED_TO_PRODUCTION` (ready_for_production -> production/closed).

*Constraint:* Ensure the `reason` column in these generated logs contains realistic Indonesian administrative explanations, avoiding code placeholders.

---

## Verification Step
Run the following execution command to ensure database integrity and verify that no Foreign Key (FK) constraint violations or Enum type mismatches occur:
```bash
php artisan db:seed --class=TransactionDataSeeder