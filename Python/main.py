from Menu import Menu

RED = "\033[31m"
BLUE = "\033[34m"
GREEN = "\033[32m"
RESET = "\033[0m"

# idx untuk kolom id
idx = 1


# helper cetak tabel
def print_line(widths):
    line = "+"
    for w in widths:
        line += "-" * (w + 2) + "+"
    print(line)


def print_row(cells, widths):
    row = "|"
    for cell, w in zip(cells, widths):
        row += " " + str(cell).ljust(w) + " |"
    print(row)

# untuk menampilkan pesan error ketika kosong
def zeroo():
    print(RED
          + "Pesanan yang anda pilih masih kosong!\n"
          + "Tambahkan setidaknya satu menu untuk menjalankan perintah ini."
          + RESET)


# fungsi panduan untuk menampilkan panduan penggunaan program
def panduan():
    widths = [8, 51, 42]
    print("Panduan penambahan menu pada bioskop X")
    print_line(widths)
    print_row(["Perintah", "Format Penggunaan", "Keterangan"], widths)
    print_line(widths)
    print_row(["add", "add (nama) (food/beverage) (rasa) (harga)", "Menambahkan satu menu pada katalog."], widths)
    print_row(["remove", "remove (id)", "Menghapus satu menu dari katalog."], widths)
    print_row(["show", "show", "Menampilkan seluruh katalog menu."], widths)
    print_row(["update", "update (id) (nama) (food/beverage) (rasa) (harga)", "Mengubah satu menu pada katalog."], widths)
    print_row(["search", "search (nama)", "Mencari apakah menu ada di katalog."], widths)
    print_row(["done", "done", "Mengakhiri sesi program."], widths)
    print_line(widths)
    print()


# fungsi add
def add(v, tokens):
    global idx
    if len(tokens) < 4:
        print(RED + "Format salah. Gunakan: add (nama) (food/beverage) (rasa) (harga)" + RESET)
        return

    nama, fnb, rasa, harga = tokens[0], tokens[1], tokens[2], tokens[3]
    if not harga.isdigit():
        print(RED + "Format salah. Gunakan: add (nama) (food/beverage) (rasa) (harga)" + RESET)
        return

    temp = Menu(idx, nama, fnb, rasa, int(harga))
    v.append(temp)

    print(GREEN + f"Menu \"{nama}\" berhasil ditambahkan dengan id {idx}." + RESET)
    idx += 1

# fungsi show
def show(v):
    widths = [4, 20, 10, 10, 10]
    print("Daftar Menu:")
    print_line(widths)
    print_row(["ID", "Nama", "Tipe", "Rasa", "Harga"], widths)
    print_line(widths)
    for item in v:
        print_row([item.get_id(), item.get_nama(), item.get_fnb(), item.get_rasa(), item.get_price()], widths)
    print_line(widths)

# fungsi remove
def remove(v, tokens):
    if len(tokens) < 1 or not tokens[0].isdigit():
        print(RED + "Format salah. Gunakan: remove (id)" + RESET)
        return

    id_ = int(tokens[0])
    for item in v:
        if item.get_id() == id_:
            v.remove(item)
            print(GREEN + f"Menu dengan id {id_} berhasil dihapus." + RESET)
            return
    print(RED + f"Menu dengan id {id_} tidak ditemukan." + RESET)

# fungsi update
def update(v, tokens):
    if len(tokens) < 5:
        print(RED + "Format salah. Gunakan: update (id) (nama) (food/beverage) (rasa) (harga)" + RESET)
        return

    id_str, nama, fnb, rasa, harga = tokens[0], tokens[1], tokens[2], tokens[3], tokens[4]
    if not id_str.isdigit() or not harga.isdigit():
        print(RED + "Format salah. Gunakan: update (id) (nama) (food/beverage) (rasa) (harga)" + RESET)
        return

    id_ = int(id_str)
    for item in v:
        if item.get_id() == id_:
            item.set_nama(nama)
            item.set_fnb(fnb)
            item.set_rasa(rasa)
            item.set_price(int(harga))
            print(GREEN + f"Menu dengan id {id_} berhasil diperbarui." + RESET)
            return
    print(RED + f"Menu dengan id {id_} tidak ditemukan." + RESET)

# fungsi search
def search(v, tokens):
    if len(tokens) < 1:
        print(RED + "Format salah. Gunakan: search (nama)" + RESET)
        return

    nama = tokens[0]
    found = False
    widths = [4, 20, 10, 10, 10]

    for item in v:
        if item.get_nama() == nama:
            if not found:
                print(GREEN + "Menu ditemukan!" + RESET)
                print_line(widths)
                print_row(["ID", "Nama", "Tipe", "Rasa", "Harga"], widths)
                print_line(widths)
            print_row([item.get_id(), item.get_nama(), item.get_fnb(), item.get_rasa(), item.get_price()], widths)
            found = True

    if found:
        print_line(widths)
    else:
        print(RED + f"Menu \"{nama}\" tidak ditemukan." + RESET)


def main():
    print(BLUE + "Selamat datang di Bioskop kami!" + RESET)
    print("(Ketik panduan untuk menampilkan panduan.)")
    print(BLUE + "Apa yang akan anda perintahkan hari ini?" + RESET)

    v = []

    while True:
        try:
            command_line = input("|| ")
        except EOFError:
            break

        tokens = command_line.split()
        if not tokens:
            continue  # baris kosong, minta lagi

        input_cmd = tokens[0].lower()
        args = tokens[1:]

        if input_cmd == "done":
            break

        kosong = len(v) == 0

        if input_cmd == "add":
            add(v, args)
        elif input_cmd == "remove":
            zeroo() if kosong else remove(v, args)
        elif input_cmd == "show":
            zeroo() if kosong else show(v)
        elif input_cmd == "update":
            zeroo() if kosong else update(v, args)
        elif input_cmd == "search":
            zeroo() if kosong else search(v, args)
        elif input_cmd == "panduan":
            panduan()
        else:
            print(RED + "Perintah tidak dikenali. Ketik 'panduan' untuk melihat daftar perintah." + RESET)

        print(BLUE + "Apakah ada yang ingin anda perintahkan lagi?" + RESET)

    print(GREEN + "Terimakasih dan silahkan datang kembali!" + RESET)


if __name__ == "__main__":
    main()