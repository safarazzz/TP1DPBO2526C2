public class Menu {
    private int id;
    private String nama;
    private String fnb;
    private String rasa;
    private int price;

    // constructor kosong
    public Menu() {
        this.id = 0;
        this.nama = "";
        this.fnb = "";
        this.rasa = "";
        this.price = 0;
    }

    // constructor berparameter
    public Menu(int id, String nama, String fnb, String rasa, int price) {
        this.id = id;
        this.nama = nama;
        this.fnb = fnb;
        this.rasa = rasa;
        this.price = price;
    }

    // getter and setter id
    public void setId(int id) { this.id = id; }
    public int getId() { return id; }

    // getter and setter nama
    public void setNama(String nama) { this.nama = nama; }
    public String getNama() { return nama; }

    // getter and setter fnb
    public void setFnb(String fnb) { this.fnb = fnb; }
    public String getFnb() { return fnb; }

    // getter and setter rasa
    public void setRasa(String rasa) { this.rasa = rasa; }
    public String getRasa() { return rasa; }

    // getter and setter price
    public void setPrice(int price) { this.price = price; }
    public int getPrice() { return price; }
}