use std::io::{self, BufRead};

// Enum cho các khu vực chịu trách nhiệm
#[derive(Debug)]
enum KhuVuc {
    CongChinh,          // Cổng chính
    KhuSanXuat,         // Khu sản xuất/xưởng
    KhoHang,            // Kho hàng
    VanPhong,           // Văn phòng
    ToanKhuVuc,         // Toàn khu vực (tuần tra tổng quát)
}

// Enum cho các nhiệm vụ chính (phạm vi hoạt động)
#[derive(Debug)]
enum NhiemVu {
    KiemSoatRaVao,      // Kiểm soát ra vào, kiểm tra giấy tờ
    TuanTraGiamSat,     // Tuần tra, giám sát camera
    BaoVeTaiSan,        // Bảo vệ tài sản, ngăn chặn trộm cắp
    XuLySuCo,           // Xử lý sự cố, báo cáo bất thường
    QuanLyThietBiAnNinh,// Quản lý thiết bị PCCC, điện nước
}

// Struct đại diện cho một nhân viên bảo vệ
#[derive(Debug)]
struct NhanVienBaoVe {
    ten: String,
    ma_so: String,
    khu_vuc: KhuVuc,
    nhiem_vu_chinh: Vec<NhiemVu>,  // Một nhân viên có thể có nhiều nhiệm vụ
}

fn main() {
    let mut danh_sach_nv: Vec<NhanVienBaoVe> = Vec::new();

    // Thêm một số dữ liệu ví dụ
    danh_sach_nv.push(NhanVienBaoVe {
        ten: "Nguyễn Văn A".to_string(),
        ma_so: "BV001".to_string(),
        khu_vuc: KhuVuc::CongChinh,
        nhiem_vu_chinh: vec![
            NhiemVu::KiemSoatRaVao,
            NhiemVu::BaoVeTaiSan,
        ],
    });

    danh_sach_nv.push(NhanVienBaoVe {
        ten: "Trần Thị B".to_string(),
        ma_so: "BV002".to_string(),
        khu_vuc: KhuVuc::KhuSanXuat,
        nhiem_vu_chinh: vec![
            NhiemVu::TuanTraGiamSat,
            NhiemVu::XuLySuCo,
        ],
    });

    loop {
        println!("\n=== QUẢN LÝ NHÂN VIÊN BẢO VỆ ===");
        println!("1. Hiển thị danh sách nhân viên");
        println!("2. Thêm nhân viên mới");
        println!("3. Thoát");
        print!("Chọn chức năng: ");

        let stdin = io::stdin();
        let mut input = String::new();
        stdin.lock().read_line(&mut input).unwrap();
        let choice = input.trim().parse::<u32>().unwrap_or(0);

        match choice {
            1 => hien_thi_danh_sach(&danh_sach_nv),
            2 => them_nhan_vien(&mut danh_sach_nv),
            3 => {
                println!("Tạm biệt!");
                break;
            }
            _ => println!("Lựa chọn không hợp lệ!"),
        }
    }
}

fn hien_thi_danh_sach(danh_sach: &Vec<NhanVienBaoVe>) {
    if danh_sach.is_empty() {
        println!("Chưa có nhân viên nào.");
        return;
    }

    for nv in danh_sach {
        println!("\n- Tên: {}", nv.ten);
        println!("  Mã số: {}", nv.ma_so);
        println!("  Khu vực chịu trách nhiệm: {:?}", nv.khu_vuc);
        println!("  Nhiệm vụ chính:");
        for nv_chinh in &nv.nhiem_vu_chinh {
            println!("    • {:?}", nv_chinh);
        }
    }
}

fn them_nhan_vien(danh_sach: &mut Vec<NhanVienBaoVe>) {
    let stdin = io::stdin();
    let mut ten = String::new();
    let mut ma_so = String::new();

    print!("Nhập tên nhân viên: ");
    stdin.lock().read_line(&mut ten).unwrap();
    print!("Nhập mã số nhân viên: ");
    stdin.lock().read_line(&mut ma_so).unwrap();

    println!("Chọn khu vực (nhập số):");
    println!("1. Cổng chính\n2. Khu sản xuất\n3. Kho hàng\n4. Văn phòng\n5. Toàn khu vực");
    let mut kv_input = String::new();
    stdin.lock().read_line(&mut kv_input).unwrap();
    let khu_vuc = match kv_input.trim().parse::<u32>().unwrap_or(0) {
        1 => KhuVuc::CongChinh,
        2 => KhuVuc::KhuSanXuat,
        3 => KhuVuc::KhoHang,
        4 => KhuVuc::VanPhong,
        _ => KhuVuc::ToanKhuVuc,
    };

    let mut nhiem_vu_chinh = Vec::new();
    println!("Chọn nhiệm vụ chính (nhập số, cách nhau bằng dấu phẩy, ví dụ: 1,3,5):");
    println!("1. Kiểm soát ra vào\n2. Tuần tra giám sát\n3. Bảo vệ tài sản\n4. Xử lý sự cố\n5. Quản lý thiết bị an ninh");
    let mut nv_input = String::new();
    stdin.lock().read_line(&mut nv_input).unwrap();

    for num in nv_input.split(',').map(|s| s.trim().parse::<u32>().unwrap_or(0)) {
        match num {
            1 => nhiem_vu_chinh.push(NhiemVu::KiemSoatRaVao),
            2 => nhiem_vu_chinh.push(NhiemVu::TuanTraGiamSat),
            3 => nhiem_vu_chinh.push(NhiemVu::BaoVeTaiSan),
            4 => nhiem_vu_chinh.push(NhiemVu::XuLySuCo),
            5 => nhiem_vu_chinh.push(NhiemVu::QuanLyThietBiAnNinh),
            _ => {},
        }
    }

    danh_sach.push(NhanVienBaoVe {
        ten: ten.trim().to_string(),
        ma_so: ma_so.trim().to_string(),
        khu_vuc,
        nhiem_vu_chinh,
    });

    println!("Đã thêm nhân viên thành công!");
}
