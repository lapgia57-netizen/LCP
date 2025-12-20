// badwords-generator.js
// Kịch bản sinh câu khẩu nghiệp ngẫu nhiên (chỉ mang tính minh họa)

// Danh sách các từ/cụm từ thô tục phổ biến (có thể mở rộng)
const tuChui = [
  "địt mẹ", "đm", "đmm", "con cặc", "cặc", "lồn", "đéo", "vcl", "vl", 
  "đjt mẹ", "đụ mẹ", "mẹ mày", "bố mày", "đĩ", "cave", "thằng chó", 
  "con chó", "đồ ngu", "óc chó", "đầu buồi", "buồi", "đầu đất"
];

const tuBoTro = [
  "mày", "tao", "nó", "we", "mẹ kiếp", "cha nội", "thằng", "con", "đồ"
];

const cauMau = [
  "{bo} {chui} chứ!",
  "{chui} {bo} luôn!",
  "Đi mà {chui} đi {bo} ơi!",
  "{bo} {chui} à?",
  "Thằng {bo} này {chui} thật!",
  "{chui} {chui} {chui}!!!",
  "Mệt {chui} với {bo} quá đi",
  "Đừng có {chui} nữa {bo}!"
];

// Hàm sinh câu ngẫu nhiên
function sinhKhauNghiep() {
  const mau = cauMau[Math.floor(Math.random() * cauMau.length)];
  
  let cau = mau
    .replace(/{chui}/g, () => tuChui[Math.floor(Math.random() * tuChui.length)])
    .replace(/{bo}/g, () => tuBoTro[Math.floor(Math.random() * tuBoTro.length)]);
    
  return cau.charAt(0).toUpperCase() + cau.slice(1);
}

// In ra console 10 câu ví dụ
console.log("=== Kịch bản khẩu nghiệp tựu động ===\n");
for (let i = 0; i < 10; i++) {
  console.log(`${i+1}. ${sinhKhauNghiep()}`);
}

// Xuất hàm để dùng ở nơi khác (nếu cần)
module.exports = { sinhKhauNghiep };
