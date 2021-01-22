# unilink_rekrutacja

Instalacja

    ściągnij repozytorium
    composer install
    w pliku .env wprowadź swoje dane aby połączyć się z bazą danych
    symfony server:start
    bin/console doctrine:database:create
    bin/console doctrine:migrations:migrate
    bin/console doctrine:fixtures:load aby utworzyć w bazie dwa konta użytkownika
        1. login: admin, hasło: adminToMoc1234
        2. login: user_one, hasło: MocneHaslo0987

Aby korzystać z aplikacji użytkownik musi być zalogowany, po zalogowaniu wyświetla się lista zadań nie wykonanych
Zalogowany użytkownik może zarządzać swoimi listami, przełączać się na widoki z listami wykonanymi, do wykonania i wszystkimi, a także może tworzyć etykiety które można przypisywać do list. Dodana etykieta jest dostępna dla wszystkich użytkowników systemu. login:
Tworząc nową listę użytkownik może przypisać wybrane etykiety, ustawić jej priorytet, nadać jej nazwę i dodać opis.
Następnie w podglądzie listy może dodać elementy listy tego zadania.
Aby nie tracić czasu na dynamiczne dodawanie pól z elementami do formularza, to zrobiłem tak że jednorazowo można dodać 10 nowych elementów do listy, jeśli lista ma zawierać więcej elementów to trzeba wejść w dany formularz kilkukrotnie.
Przy kolejnym wyświetleniu formularza użytkownik może edytować poprzednio dodane elementy listy jak i dodać do 10 nowych elementów.
użytkownik może pojedyńczo może oznaczyć że został wykonany, lub odznaczyć że jednak jest do zrobienia, a także może oznaczyć wszystkie elementy listy jako oznaczone klikając w przycisk pod listą elementów.
Listę można oznaczyć jako wykonaną tylko w przypadku jeśli wszystkie elementy listy są wykonane lub lista nie posiada żadnych elementów.
W widoku podglądu listy znajduje się link do udostepniania listy dla innych osób.
Do zobaczenia udostępnionej listy, osoba która posiada link nie musi posiadać konta.