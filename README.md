Ninieszejsze repozytorium zawiera projekt zaliczeniowy z przedmiotu Języki programowania - PHP. Dla zapewnienia sprawności w ocenianiu, dostosowano repozytorium by korzystało z dockera.

Pliki php należy umieścić w folderze `public_html`, który jest automatycznie kopiowany w odpowiednie miejsce kontenera przy jego budowaniu. Bazę danych MySQL należy wyeksportować do pliku sql i umieścić jako `database.sql`. Wtedy przy pierwszym zbudowaniu zostaną wykonane polecenia z pliku. Plik `project.conf` zawiera podstawową konfigurację serwera, którą można zmodyfikować. Jest ona automatycznie kopiowana w odpowiednie miejsce kontenera.

Studenci bardziej oswojeni z pracą z dockerem mogą dowolnie modyfikować `dockerfile`, `docker-compose.yml`, jak i całą strukturę repozytorium.

Do uruchomienia projektu lokalnie należy sklonować repozytorium poleceniem `git clone` a następnie wywołać `docker-compose up`. Projekt uznaje się za poprawnie działający, gdy w wyniku tych poleceń strona działa. W ten sposób też będzie uruchamiany projekt w celu ocenienia go, dlatego należy się upewnić, że wszystko uruchamia się poprawnie w kontenerze.

Jeżeli przed uruchomieniem projektu potrzeba wykonać dodatkowe czynności, np. związane z doinstalowaniem jakiejś biblioteki, należy je zawrzeć w `dockerfile` lub `docker-compose.yml`.
