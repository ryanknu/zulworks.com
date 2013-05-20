var collab = (function() {
    
    var db_url = '/collab/get-db';
    var key_url = '/collab/get-key';
    var balance_url = '/collab/get-balance';
    var ledger_url = '/collab/get-ledger';
    var db_loaded = false;
    var db_path = '';
    var user = null;
    
    // key_cached is used for if cookies are disabled
    var key_cached = '';
    
    var getDb = function() {
        $.get(db_url, function(data) {
            if (data.res == 'No') {
                $(document).trigger('ZulCollabEventDatabaseLoadFailure');
            } else {
                // data.res is db path
                db_loaded = true;
                db_path = data.res;
                $(document).trigger('ZulCollabEventDatabaseLoaded');
            }
        });
    }
    
    var getKey = function() {
        if ( !db_loaded ) {
            alert('db not loaded');
            return;
        }
        
        $.post(key_url, {
            dir: db_path,
            user: $('#login-area #user').val(),
            pass: $('#login-area #pass').val()
        }, function(data) {
            if ( data.res == 'No' ) {
                if ( $('#login-area #user').val().length > 0 ) {
                    $('#login-area #invalid').show();
                }
                $(document).trigger('ZulCollabNoKeyAvailable');
            } else {
                key_cached = data.res;
                user = data.user;
                document.cookie = "collab-key=" + data.res + "; expires=2014-01-01;";
                $(document).trigger('ZulCollabKeyAvailable');
            }
        });
    };
    
    var getBalances = function() {
        if ( !db_loaded ) {
            alert('db not loaded');
            return;
        }
        
        $.post(balance_url, {
            dir: db_path,
            key: key_cached
        }, function(data) {
            $('#accounts').html('');
            for(i = 0; i < data.data.length; i++) {
                obj = data.data[i];
                div = $('<div/>');
                div.html('<div class="big left">' + obj.name + '</div><div class="big right">' + obj.balance + '</div><div style="clear:both"></div>');
                $('#accounts').append(div);
            }
        })
    };
    
    var getLedger = function() {
        if ( !db_loaded ) {
            alert('db not loaded');
            return;
        }
        
        $.post(ledger_url, {
            dir: db_path,
            key: key_cached
        }, function(data) {
            $('#ledger').html('');
            for(i = 0; i < data.data.length; i++) {
                obj = data.data[i];
                div = $('<div/>');
                div.html('<div class="ledger left">' + obj.name + '</div><div class="ledger right">' + obj.amount + '</div><div style="clear:both"></div>');
                $('#ledger').append(div);
            }
        })
    };
    
    var getUser = function() {
        return user;
    };
    
    return {
        getKey: getKey,
        getDb: getDb,
        getUser: getUser,
        getBalances: getBalances,
        getLedger: getLedger
    };
    
})();

$(document).bind('ZulCollabKeyAvailable', null, function() {
    $('#main-area > *').hide();
    $('#authenticated-area').show();
    collab.getBalances();
    collab.getLedger();
});

$(document).bind('ZulCollabNoKeyAvailable', null, function() {
    $('#main-area > *').hide();
    $('#login-area').show();
});

$(document).bind('ZulCollabEventDatabaseLoaded', null, function() {
    // show login, or check cookie
    collab.getKey();
});

$(document).bind('ZulCollabEventDatabaseLoadFailure', null, function() {
    // show login, or check cookie
    $('#main-area > *').hide();
    $('#db-load-error').show();
});

$(document).ready(function() {
    collab.getDb();
    $('#login-area').submit(function() { collab.getKey(); return false; } );
});
