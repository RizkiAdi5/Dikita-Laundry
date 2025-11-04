
# Dikita Laundry Management System

Sistem Manajemen Laundry berbasis Laravel dengan sistem role-based access control (RBAC).

## 🔐 Kredensial Login

### Super Admin:
- **Email:** admin@dikitalaundry.com
- **Password:** Rakim$%**

### Admin:
- **Email:** admin.user@dikitalaundry.com
- **Password:** admin123

### Manager:
- **Email:** manager@dikitalaundry.com
- **Password:** manager123

### Cashier:
- **Email:** cashier@dikitalaundry.com
- **Password:** cashier123

### Operator:
- **Email:** operator@dikitalaundry.com
- **Password:** operator123

### Staff:
- **Email:** staff@dikitalaundry.com
- **Password:** staff123

---

## 👥 Hak Akses Berdasarkan Role

### 🔴 Super Admin (Full Access)
**Akses Penuh ke Semua Fitur**

| Menu | View | Create | Edit | Delete | Approve |
|------|------|--------|------|--------|---------|
| 📊 Dashboard | ✅ | - | - | - | - |
| 🛒 Pesanan | ✅ | ✅ | ✅ | ✅ | - |
| 👥 Pelanggan | ✅ | ✅ | ✅ | ✅ | - |
| 🔔 Layanan | ✅ | ✅ | ✅ | ✅ | - |
| 📦 Inventori | ✅ | ✅ | ✅ | ✅ | - |
| 👔 Karyawan | ✅ | ✅ | ✅ | ✅ | - |
| 💰 Pengeluaran | ✅ | ✅ | ✅ | ✅ | ✅ |
| 📈 Laporan | ✅ | - | - | - | - |

**Deskripsi:**
- Memiliki akses penuh ke seluruh sistem
- Dapat mengelola semua data tanpa batasan
- Dapat melihat semua laporan dan analitik
- Dapat approve/reject pengeluaran
- Dapat mengelola karyawan dan user

---

### 🟠 Admin
**Akses Hampir Penuh (Kecuali Beberapa Fitur Sensitif)**

| Menu | View | Create | Edit | Delete | Approve |
|------|------|--------|------|--------|---------|
| 📊 Dashboard | ✅ | - | - | - | - |
| 🛒 Pesanan | ✅ | ✅ | ✅ | ✅ | - |
| 👥 Pelanggan | ✅ | ✅ | ✅ | ✅ | - |
| 🔔 Layanan | ✅ | ✅ | ✅ | ✅ | - |
| 📦 Inventori | ✅ | ✅ | ✅ | ✅ | - |
| 👔 Karyawan | ✅ | ✅ | ✅ | ✅ | - |
| 💰 Pengeluaran | ✅ | ✅ | ✅ | ✅ | ✅ |
| 📈 Laporan | ✅ | - | - | - | - |

**Deskripsi:**
- Dapat mengelola hampir semua aspek bisnis
- Dapat mengelola karyawan dan user
- Dapat approve/reject pengeluaran
- Dapat melihat semua laporan
- Cocok untuk: Pemilik bisnis, Manager senior

---

### 🟡 Manager
**Akses Manajemen Operasional**

| Menu | View | Create | Edit | Delete | Approve |
|------|------|--------|------|--------|---------|
| 📊 Dashboard | ✅ | - | - | - | - |
| 🛒 Pesanan | ✅ | ✅ | ✅ | ✅ | - |
| 👥 Pelanggan | ✅ | ✅ | ✅ | ✅ | - |
| 🔔 Layanan | ✅ | ✅ | ✅ | ✅ | - |
| 📦 Inventori | ✅ | ✅ | ✅ | ✅ | - |
| 👔 Karyawan | ❌ | ❌ | ❌ | ❌ | - |
| 💰 Pengeluaran | ✅ | ✅ | ✅ | ✅ | ✅ |
| 📈 Laporan | ✅ | - | - | - | - |

**Deskripsi:**
- Fokus pada operasional harian
- Dapat mengelola pesanan, pelanggan, layanan
- Dapat mengelola inventori dan stok
- Dapat approve/reject pengeluaran
- Dapat melihat laporan performa dan keuangan
- **TIDAK BISA** mengelola karyawan
- Cocok untuk: Manager operasional, Supervisor

---

### 🟢 Cashier (Kasir)
**Akses Front Office & Transaksi**

| Menu | View | Create | Edit | Delete | Approve |
|------|------|--------|------|--------|---------|
| 📊 Dashboard | ✅ | - | - | - | - |
| 🛒 Pesanan | ✅ | ✅ | ✅ | ❌ | - |
| 👥 Pelanggan | ✅ | ✅ | ✅ | ❌ | - |
| 🔔 Layanan | ❌ | ❌ | ❌ | ❌ | - |
| 📦 Inventori | ❌ | ❌ | ❌ | ❌ | - |
| 👔 Karyawan | ❌ | ❌ | ❌ | ❌ | - |
| 💰 Pengeluaran | ❌ | ❌ | ❌ | ❌ | ❌ |
| 📈 Laporan | ❌ | - | - | - | - |

**Deskripsi:**
- Fokus pada transaksi dan pelayanan pelanggan
- Dapat membuat dan mengedit pesanan
- Dapat menambah dan mengedit data pelanggan
- **TIDAK BISA** menghapus pesanan atau pelanggan
- **TIDAK BISA** mengakses inventori, karyawan, pengeluaran
- **TIDAK BISA** melihat laporan
- Cocok untuk: Kasir, Front desk

---

### 🔵 Operator
**Akses Operasional & Inventori**

| Menu | View | Create | Edit | Delete | Approve |
|------|------|--------|------|--------|---------|
| 📊 Dashboard | ✅ | - | - | - | - |
| 🛒 Pesanan | ✅ | ✅ | ✅ | ❌ | - |
| 👥 Pelanggan | ❌ | ❌ | ❌ | ❌ | - |
| 🔔 Layanan | ❌ | ❌ | ❌ | ❌ | - |
| 📦 Inventori | ✅ | ✅ | ✅ | ❌ | - |
| 👔 Karyawan | ❌ | ❌ | ❌ | ❌ | - |
| 💰 Pengeluaran | ❌ | ❌ | ❌ | ❌ | ❌ |
| 📈 Laporan | ❌ | - | - | - | - |

**Deskripsi:**
- Fokus pada operasional produksi dan inventori
- Dapat melihat dan update status pesanan
- Dapat mengelola stok dan inventori
- **TIDAK BISA** menghapus data
- **TIDAK BISA** mengakses data pelanggan, karyawan, pengeluaran
- **TIDAK BISA** melihat laporan
- Cocok untuk: Operator mesin cuci, Staff gudang

---

### 🟣 Staff
**Akses Terbatas untuk Tugas Harian**

| Menu | View | Create | Edit | Delete | Approve |
|------|------|--------|------|--------|---------|
| 📊 Dashboard | ✅ | - | - | - | - |
| 🛒 Pesanan | ✅ | ✅ | ✅ | ❌ | - |
| 👥 Pelanggan | ✅ | ✅ | ✅ | ❌ | - |
| 🔔 Layanan | ✅ | ❌ | ❌ | ❌ | - |
| 📦 Inventori | ✅ | ❌ | ❌ | ❌ | - |
| 👔 Karyawan | ❌ | ❌ | ❌ | ❌ | - |
| 💰 Pengeluaran | ✅ | ✅ | ❌ | ❌ | ❌ |
| 📈 Laporan | ❌ | - | - | - | - |

**Deskripsi:**
- Akses paling terbatas untuk tugas harian
- Dapat membuat dan edit pesanan
- Dapat menambah dan edit pelanggan
- Dapat melihat (view only) layanan dan inventori
- Dapat membuat catatan pengeluaran (perlu approval)
- **TIDAK BISA** menghapus data apapun
- **TIDAK BISA** approve pengeluaran
- **TIDAK BISA** mengakses karyawan dan laporan
- Cocok untuk: Staff part-time, Trainee

---

## 📋 Ringkasan Akses Menu

### Dashboard 📊
- ✅ **Semua Role** dapat mengakses

### Pesanan 🛒
- ✅ **Create:** Super Admin, Admin, Manager, Cashier, Operator, Staff
- ✅ **Edit:** Super Admin, Admin, Manager, Cashier, Staff
- ✅ **Delete:** Super Admin, Admin, Manager

### Pelanggan 👥
- ✅ **Create:** Super Admin, Admin, Manager, Cashier, Staff
- ✅ **Edit:** Super Admin, Admin, Manager, Cashier, Staff
- ✅ **Delete:** Super Admin, Admin, Manager

### Layanan 🔔
- ✅ **View:** Super Admin, Admin, Manager, Staff
- ✅ **Create/Edit/Delete:** Super Admin, Admin, Manager

### Inventori 📦
- ✅ **View:** Super Admin, Admin, Manager, Operator, Staff
- ✅ **Create/Edit:** Super Admin, Admin, Manager, Operator
- ✅ **Delete:** Super Admin, Admin, Manager

### Karyawan 👔
- ✅ **Full Access:** Super Admin, Admin
- ❌ **No Access:** Manager, Cashier, Operator, Staff

### Pengeluaran