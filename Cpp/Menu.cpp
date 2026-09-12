#include <bits/stdc++.h>
using namespace std;

class Menu{
    private :
        int id;
        string nama;
        string fnb;
        string rasa;
        int price;
    public :
        // constructor kosong
        Menu(){
            id=0;
            nama="";
            fnb="";
            rasa="";
            price=0;
        };
        // constructor berparameter
        Menu(int id, string nama, string fnb, string rasa, int price) {
            this->id=id;
            this->nama=nama;
            this->fnb=fnb;
            this->rasa=rasa;
            this->price=price;
        }
        // getter and setter id
        void setId(int id){this->id=id;}
        int getId() {return id;}

        // getter and setter nama
        void setNama(string nama){this->nama=nama;}
        string getNama() {return nama;}

        // getter and setter fnb
        void setFnb(string fnb){this->fnb=fnb;}
        string getFnb() {return fnb;}

        // getter and setter rasa
        void setRasa(string rasa){this->rasa=rasa;}
        string getRasa() {return rasa;}

        // getter and setter price
        void setPrice(int price){this->price=price;}
        int getPrice() {return price;}
        
        // destructor
        ~Menu(){}
};