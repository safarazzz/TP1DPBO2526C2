import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;
import java.util.StringTokenizer;

public class Main {
    // pewarnaan teks untuk menampilkan pesan error, sukses, dan informasi
    static final String RED = "\033[31m";
    static final String BLUE = "\033[34m";
    static final String GREEN = "\033[32m";
    static final String RESET = "\033[0m";

    // helper pencetak table
    static void printLine(int[] widths) {
        StringBuilder sb = new StringBuilder("+");
        for (int w : widths) {
            sb.append("-".repeat(w + 2)).append("+");
        }
        System.out.println(sb);
    }

    static void printRow(String[] cells, int[] widths) {
        StringBuilder sb = new StringBuilder("|");
        for (int i = 0; i < cells.length; i++) {
            sb.append(" ").append(padRight(cells[i], widths[i])).append(" |");
        }
        System.out.println(sb);
    }

    static String padRight(String s, int width) {
        if (s.length() >= width) return s;
        return s + " ".repeat(width - s.length());
    }

    // buat nampilin pesan error kalo pesanan kosong
    static void zeroo() {
        System.out.println(RED
                + "Pesanan yang anda pilih masih kosong!\n"
                + "Tambahkan setidaknya satu menu untuk menjalankan perintah ini."
                + RESET);
    }

    // fungsi panduan untuk menampilkan panduan penggunaan program
    static void panduan() {
        int[] widths = {8, 51, 42};
        System.out.println("Panduan penambahan menu pada bioskop X");
        printLine(widths);
        printRow(new String[]{"Perintah", "Format Penggunaan", "Keterangan"}, widths);
        printLine(widths);
        printRow(new String[]{"add", "add (nama) (food/beverage) (rasa) (harga)", "Menambahkan satu menu pada katalog."}, widths);
        printRow(new String[]{"remove", "remove (id)", "Menghapus satu menu dari katalog."}, widths);
        printRow(new String[]{"show", "show", "Menampilkan seluruh katalog menu."}, widths);
        printRow(new String[]{"update", "update (id) (nama) (food/beverage) (rasa) (harga)", "Mengubah satu menu pada katalog."}, widths);
        printRow(new String[]{"search", "search (nama)", "Mencari apakah menu ada di katalog."}, widths);
        printRow(new String[]{"done", "done", "Mengakhiri sesi program."}, widths);
        printLine(widths);
        System.out.println();
    }

    static int idx = 1; // variabel buat id otomatis

    // fungsi-fungsi untuk menambahkan, menampilkan, menghapus, memperbarui, dan mencari menu
    static void add(List<Menu> v, StringTokenizer st) {
        if (st.countTokens() < 4) {
            System.out.println(RED + "Format salah. Gunakan: add (nama) (food/beverage) (rasa) (harga)" + RESET);
            return;
        }
        String nama = st.nextToken();
        String fnb = st.nextToken();
        String rasa = st.nextToken();
        int price;
        // ini catch kalo harganya malah string
        try {
            price = Integer.parseInt(st.nextToken());
        } catch (NumberFormatException e) {
            System.out.println(RED + "Format salah. Gunakan: add (nama) (food/beverage) (rasa) (harga)" + RESET);
            return;
        }

        Menu temp = new Menu(idx, nama, fnb, rasa, price);
        v.add(temp);

        System.out.println(GREEN + "Menu \"" + nama + "\" berhasil ditambahkan dengan id " + idx + "." + RESET);
        idx++;
    }

    static void show(List<Menu> v) {
        int[] widths = {4, 20, 10, 10, 10};
        System.out.println("Daftar Menu:");
        printLine(widths);
        printRow(new String[]{"ID", "Nama", "Tipe", "Rasa", "Harga"}, widths);
        printLine(widths);
        for (Menu item : v) {
            printRow(new String[]{
                    String.valueOf(item.getId()),
                    item.getNama(),
                    item.getFnb(),
                    item.getRasa(),
                    String.valueOf(item.getPrice())
            }, widths);
        }
        printLine(widths);
    }

    static void remove(List<Menu> v, StringTokenizer st) {
        if (!st.hasMoreTokens()) {
            System.out.println(RED + "Format salah. Gunakan: remove (id)" + RESET);
            return;
        }
        int id;
        try {
            id = Integer.parseInt(st.nextToken());
        } catch (NumberFormatException e) {
            System.out.println(RED + "Format salah. Gunakan: remove (id)" + RESET);
            return;
        }

        for (int i = 0; i < v.size(); i++) {
            if (v.get(i).getId() == id) {
                v.remove(i);
                System.out.println(GREEN + "Menu dengan id " + id + " berhasil dihapus." + RESET);
                return;
            }
        }
        System.out.println(RED + "Menu dengan id " + id + " tidak ditemukan." + RESET);
    }

    static void update(List<Menu> v, StringTokenizer st) {
        if (st.countTokens() < 5) {
            System.out.println(RED + "Format salah. Gunakan: update (id) (nama) (food/beverage) (rasa) (harga)" + RESET);
            return;
        }
        int id;
        try {
            id = Integer.parseInt(st.nextToken());
        } catch (NumberFormatException e) {
            System.out.println(RED + "Format salah. Gunakan: update (id) (nama) (food/beverage) (rasa) (harga)" + RESET);
            return;
        }
        String nama = st.nextToken();
        String fnb = st.nextToken();
        String rasa = st.nextToken();
        int price;
        try {
            price = Integer.parseInt(st.nextToken());
        } catch (NumberFormatException e) {
            System.out.println(RED + "Format salah. Gunakan: update (id) (nama) (food/beverage) (rasa) (harga)" + RESET);
            return;
        }

        for (Menu item : v) {
            if (item.getId() == id) {
                item.setNama(nama);
                item.setFnb(fnb);
                item.setRasa(rasa);
                item.setPrice(price);
                System.out.println(GREEN + "Menu dengan id " + id + " berhasil diperbarui." + RESET);
                return;
            }
        }
        System.out.println(RED + "Menu dengan id " + id + " tidak ditemukan." + RESET);
    }

    static void search(List<Menu> v, StringTokenizer st) {
        if (!st.hasMoreTokens()) {
            System.out.println(RED + "Format salah. Gunakan: search (nama)" + RESET);
            return;
        }
        String nama = st.nextToken();
        boolean found = false;
        int[] widths = {4, 20, 10, 10, 10};

        for (Menu item : v) {
            if (item.getNama().equals(nama)) {
                if (!found) {
                    System.out.println(GREEN + "Menu ditemukan!" + RESET);
                    printLine(widths);
                    printRow(new String[]{"ID", "Nama", "Tipe", "Rasa", "Harga"}, widths);
                    printLine(widths);
                }
                printRow(new String[]{
                        String.valueOf(item.getId()),
                        item.getNama(),
                        item.getFnb(),
                        item.getRasa(),
                        String.valueOf(item.getPrice())
                }, widths);
                found = true;
            }
        }
        if (found) printLine(widths);
        else System.out.println(RED + "Menu \"" + nama + "\" tidak ditemukan." + RESET);
    }

    public static void main(String[] args) {
        System.out.println(BLUE + "Selamat datang di Bioskop kami!" + RESET);
        System.out.println("(Ketik panduan untuk menampilkan panduan.)");
        System.out.println(BLUE + "Apa yang akan anda pesan hari ini?" + RESET);

        List<Menu> v = new ArrayList<>();
        Scanner scanner = new Scanner(System.in);

        while (true) {
            System.out.print("|| ");
            if (!scanner.hasNextLine()) break;
            String commandLine = scanner.nextLine();
            StringTokenizer st = new StringTokenizer(commandLine);
            if (!st.hasMoreTokens()) continue; // baris kosong, minta lagi
            String input = st.nextToken().toLowerCase();
            if (input.equals("done")) break;

            boolean kosong = v.isEmpty();
            switch (input) {
                case "add":
                    add(v, st);
                    break;
                case "remove":
                    if (kosong) zeroo(); else remove(v, st);
                    break;
                case "show":
                    if (kosong) zeroo(); else show(v);
                    break;
                case "update":
                    if (kosong) zeroo(); else update(v, st);
                    break;
                case "search":
                    if (kosong) zeroo(); else search(v, st);
                    break;
                case "panduan":
                    panduan();
                    break;
                default:
                    System.out.println(RED + "Perintah tidak dikenali. Ketik 'panduan' untuk melihat daftar perintah." + RESET);
            }
            System.out.println(BLUE + "Apakah ada yang ingin anda tambahkan lagi?" + RESET);
        }
        System.out.println(GREEN + "Terimakasih dan silahkan datang kembali!" + RESET);
        scanner.close();
    }
}