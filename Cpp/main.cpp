#include <bits/stdc++.h>
#include "Menu.cpp"
using namespace std;
// deklarasi penyingkat dan warna
#define ln   '\n'
typedef vector<Menu> vm;
typedef Menu m;
#define RED    "\033[31m"
#define BLUE   "\033[34m"
#define GREEN  "\033[32m"
#define RESET  "\033[0m"

// helper pencetak tabel
void printLine(const vector<int>& widths) {
    cout << "+";
    for (int w : widths) cout << string(w + 2, '-') << "+";
    cout << ln;
}

void printRow(const vector<string>& cells, const vector<int>& widths) {
    cout << "|";
    for (size_t i = 0; i < cells.size(); i++) {
        cout<<" "<<left<<setw(widths[i])<<cells[i]<<" |";
    }
    cout << ln;
}

// prosedur untuk menampilkan pesan kesalahan ketika vektor menu kosong
void zeroo() {
    cout << RED
         << "Pesanan yang anda pilih masih kosong!" << ln
         << "Tambahkan setidaknya satu menu untuk menjalankan perintah ini."
         << RESET << ln;
}

// fungsi panduan untuk menampilkan panduan penggunaan program
void panduan() {
    vector<int> widths = {8, 51, 42};
    cout << "Panduan penambahan menu pada bioskop X" << ln;
    printLine(widths);
    printRow({"Perintah", "Format Penggunaan", "Keterangan"}, widths);
    printLine(widths);
    printRow({"add",     "add (nama) (food/beverage) (rasa) (harga)",        "Menambahkan satu menu pada katalog."}, widths);
    printRow({"remove",  "remove (id)",                                      "Menghapus satu menu dari katalog."}, widths);
    printRow({"show",    "show",                                             "Menampilkan seluruh katalog menu."}, widths);
    printRow({"update",  "update (id) (nama) (food/beverage) (rasa) (harga)","Mengubah satu menu pada katalog."}, widths);
    printRow({"search",  "search (nama)",                                    "Mencari apakah menu ada di katalog."}, widths);
    printRow({"done",    "done",                                             "Mengakhiri sesi program."}, widths);
    printLine(widths);
    cout << ln;
}

// prosedur untuk menambahkan menu baru ke dalam vektor menu
void add(vm &v, int &idx, istringstream &iss) {
    string nama, fnb, rasa;
    int price;
    if (!(iss>>nama>>fnb>>rasa>>price)) {
        cout << RED << "Format salah. Gunakan: add (nama) (food/beverage) (rasa) (harga)" << RESET << ln;
        return;
    }
    m temp(idx, nama, fnb, rasa, price);
    v.push_back(temp);
    cout << GREEN << "Menu \"" << nama << "\" berhasil ditambahkan dengan id " << idx << "." << RESET << ln;
    idx++;
}

// prosedur untuk menampilkan seluruh menu yang ada di dalam vektor menu
void show(vm &v) {
    vector<int> widths = {4, 20, 10, 10, 10};
    cout << "Daftar Menu yang telah anda pesan:" << ln;
    printLine(widths);
    printRow({"ID", "Nama", "Tipe", "Rasa", "Harga"}, widths);
    printLine(widths);
    for (auto &item : v) {
        printRow({
            to_string(item.getId()),
            item.getNama(),
            item.getFnb(),
            item.getRasa(),
            to_string(item.getPrice())
        }, widths);
    }
    printLine(widths);
}

// prosedur untuk menghapus menu dari vektor menu berdasarkan id
void remove(vm &v, istringstream &iss) {
    int id;
    if (!(iss>>id)) {
        cout << RED << "Format salah. Gunakan: remove (id)" << RESET << ln;
        return;
    }
    for (auto it = v.begin(); it != v.end(); ++it) {
        if (it->getId() == id) {
            v.erase(it);
            cout << GREEN << "Menu anda dengan id " << id << " berhasil dihapus." << RESET << ln;
            return;
        }
    }
    cout << RED << "Menu anda dengan id " << id << " belum anda tambahkan." << RESET << ln;
}

// prosedur untuk memperbarui menu yang ada di dalam vektor menu berdasarkan id
void update(vm &v, istringstream &iss) {
    int id;
    string nama, fnb, rasa;
    int price;
    if (!(iss >> id >> nama >> fnb >> rasa >> price)) {
        cout << RED << "Format salah. Gunakan: update (id) (nama) (food/beverage) (rasa) (harga)" << RESET << ln;
        return;
    }

    for (auto &item : v) {
        if (item.getId() == id) {
            item.setNama(nama);
            item.setFnb(fnb);
            item.setRasa(rasa);
            item.setPrice(price);
            cout << GREEN << "Menu anda dengan id " << id << " berhasil diperbarui." << RESET << ln;
            return;
        }
    }
    cout << RED << "Menu anda dengan id " << id << " belum anda tambahkan." << RESET << ln;
}

// prosedur untuk mencari menu berdasarkan nama di dalam vektor menu
void search(vm &v, istringstream &iss) {
    string nama;
    if (!(iss >> nama)) {
        cout << RED << "Format salah. Gunakan: search (nama)" << RESET << ln;
        return;
    }
    bool found = false;
    vector<int> widths = {4, 20, 10, 10, 10};
    for (auto &item : v) {
        if (item.getNama() == nama) {
            if (!found) {
                cout << GREEN << "Menu berikut sudah anda pesan:" << RESET << ln;
                printLine(widths);
                printRow({"ID", "Nama", "Tipe", "Rasa", "Harga"}, widths);
                printLine(widths);
            }
            printRow({
                to_string(item.getId()),
                item.getNama(),
                item.getFnb(),
                item.getRasa(),
                to_string(item.getPrice())
            }, widths);
            found = true;
        }
    }
    if (found) printLine(widths);
    else cout << RED << "Menu \"" << nama << "\" belum anda tambahkan." << RESET << ln;
}

int main() {
    cout<<BLUE<<"Selamat datang di Bioskop kami!"<<RESET<<ln;
    cout<<"(Ketik panduan untuk menampilkan panduan.)"<<ln;
    cout<<BLUE<<"Apa yang akan anda pesan untuk menonton hari ini?"<<RESET<<ln;
    vm v;
    int idx=1;
    string commandLine, input;
    bool masih = true;

    while(masih) {
        cout<<"|| ";
        if (!getline(cin, commandLine)) break;
        istringstream iss(commandLine);
        if (!(iss >> input)) continue;
        transform(input.begin(), input.end(), input.begin(), ::tolower);
        // kalo done langsung break
        if (input == "done") {
            masih = false;
        }else {
            bool kosong = v.empty();
            if(input=="add") {
                add(v, idx, iss);
            }else if(input=="remove") {
                if(kosong) zeroo();
                else remove(v, iss);
            }else if(input=="show") {
                if(kosong) zeroo();
                else show(v);
            }else if(input=="update") {
                if(kosong) zeroo();
                else update(v, iss);
            }else if(input=="search"){
                if(kosong) zeroo();
                else search(v, iss);
            }else if(input=="panduan"){
                panduan();
            }else {
                cout<<RED<<"Perintah tidak dikenali. Ketik 'panduan' untuk melihat daftar perintah."<<RESET<<ln;
            }
            cout<<BLUE<<"Apakah ada yang ingin anda tambahkan lagi?"<<RESET<<ln;
        }
    }
    cout<<GREEN<<"Terimakasih dan silahkan datang kembali!"<<RESET<<ln;
    return 0;
}