/* eslint-disable */
$(document).ready(function() {
    $('#messages div').each(function(i, message){
        var $message = $(message);

        $.notify({
            message: $message.html()
        }, {
            type: $message.attr('class'),
            offset: {
                x: 20,
                y: 70
            }
        });
    });

    $('.dp').datepicker({
        dateFormat: 'dd.mm.yy',
        changeMonth: true,
        changeYear: true
    });

    function isTokenSentToServer(currentToken) {
        return window.localStorage.getItem('sentFirebaseMessagingToken') === currentToken;
    }

    function setTokenSentToServer(currentToken) {
        window.localStorage.setItem(
            'sentFirebaseMessagingToken',
            currentToken || ''
        );
    }

    function sendTokenToServer(currentToken) {
        if (!isTokenSentToServer(currentToken)) {
            var url = '/admin/index/subscribeFCM'; // адрес скрипта на сервере который сохраняет ID устройства
            $.post(url, {
                token: currentToken
            }, function(response){
                if(response.saved === true) {
                    setTokenSentToServer(currentToken);
                }
            }, 'json');
        }
    }

    function subscribe() {
        // запрашиваем разрешение на получение уведомлений
        messaging.requestPermission()
            .then(function() {
                // получаем ID устройства
                messaging.getToken()
                    .then(function(currentToken) {
                        console.log(currentToken);

                        if (currentToken) {
                            sendTokenToServer(currentToken);
                        } else {
                            console.warn('Не удалось получить токен.');
                            setTokenSentToServer(false);
                        }
                    })
                    .catch(function(err){
                        console.warn('При получении токена произошла ошибка.', err);
                        setTokenSentToServer(false);
                    });
            })
            .catch(function(err) {
                console.warn('Не удалось получить разрешение на показ уведомлений.', err);
            });
    }

    if ('Notification' in window) {
        var messaging = firebase.messaging();

        // пользователь уже разрешил получение уведомлений
        // подписываем на уведомления если ещё не подписали
        if (Notification.permission === 'granted') {
            subscribe();
        }

        // по клику, запрашиваем у пользователя разрешение на уведомления
        // и подписываем его
        $('#subscribe').on('click', function() {
            subscribe();
        });
    }
});
