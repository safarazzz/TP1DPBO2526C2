class Menu:
    def __init__(self, id=0, nama="", fnb="", rasa="", price=0):
        self._id = id
        self._nama = nama
        self._fnb = fnb
        self._rasa = rasa
        self._price = price

    # getter & setter id
    def get_id(self):
        return self._id

    def set_id(self, id):
        self._id = id

    # getter & setter nama
    def get_nama(self):
        return self._nama

    def set_nama(self, nama):
        self._nama = nama

    # getter & setter fnb
    def get_fnb(self):
        return self._fnb

    def set_fnb(self, fnb):
        self._fnb = fnb

    # getter & setter rasa
    def get_rasa(self):
        return self._rasa

    def set_rasa(self, rasa):
        self._rasa = rasa

    # getter & setter price
    def get_price(self):
        return self._price

    def set_price(self, price):
        self._price = price