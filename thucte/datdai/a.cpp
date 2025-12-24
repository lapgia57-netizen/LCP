#include <iostream>
#include <iomanip>
#include <string>
using namespace std;

struct QuocGia {
    string ten;
    long long dienTich;  // km²
};

int main() {
    QuocGia ds[10] = {
        {"Nga", 17098242},
        {"Canada", 9984670},
        {"Hoa Kỳ", 9833517},
        {"Trung Quốc", 9596961},
        {"Brazil", 8515767},
        {"Úc", 7692024},
        {"Ấn Độ", 3287263},
        {"Argentina", 2780400},
        {"Kazakhstan", 2724900},
        {"Algeria", 2381741}
    };

    cout << "TOP 10 QUỐC GIA CÓ DIỆN TÍCH LỚN NHẤT THẾ GIỚI (2024-2025)\n";
    cout << string(60, '=') << "\n";
    cout << setw(4) << "STT" 
         << setw(20) << left << "Quốc gia" 
         << setw(15) << right << "Diện tích (km²)" << "\n";
    cout << string(60, '-') << "\n";

    for(int i = 0; i < 10; i++) {
        cout << setw(4) << (i+1) 
             << setw(20) << left << ds[i].ten 
             << setw(15) << right << ds[i].dienTich << "\n";
    }

    cout << "\nNguồn tham khảo: Liên Hợp Quốc & World Bank (dữ liệu cập nhật gần nhất)\n";
    return 0;
}
