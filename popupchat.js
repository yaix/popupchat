
(function( $ ) {
    
    $.fn.popupchat = function( options ) {
        var $this = null;

        // Global settings for chat.
        var settings = $.extend( {

            // CSRF protection.
            'csrf':null,

            // There is a slow or a fast interval, depending 
            // if the chatbox is open or closed.
            'slowinterval': 60000,
            'fastinterval': 5000,

            // If false, do never poll for new msgs.
            'active': true,

            // If activate it will poll as interval specifies, 
            // otherwise only manual polling.
            'auto': true,

            // Overwrite: Customize the chat partner.
            'me': { 'id':1, 'nick':'MyNick', 'chat':'You' },

            // Overwrite: Customize the chat partner.
            'them': { 'id':2, 'nick':'TheirNick', 'chat':'TheirNick'},

            // The max length for a chat line.
            'maxlength': 250,
            
            // Overwrite: Set the chat title bar text.
            'title': 'Chat with TheirNick',
    
            // Overwrite: Initial text displayed in chat box.
            'initmsg': 'This is a personal chat window.',

        }, options);

        // Additional settings.
        // --------------------

        // URI to connect to the server side.
        settings.api = './popupchat.php';

        // Init the last query time.
        settings.last = 0;
            
        // Remember the setTimeout ID.
        settings.setTimeoutId = null;

        // Poll the server, in seconds.
        settings.interval = settings.fastinterval;

        // --------------------

        var getmsgs = function() {
            if (settings.active) {
                // Find the last massage timestamp.
                settings.last = $this.find('div.b > p:last-child').attr('ref');
                if (typeof settings.last == 'undefined') settings.last = 0;
                
                // Get new messages.
                $.get(settings.api, { 'me':settings.me.id, 'them':settings.them.id, 'csrf':settings.csrf, 'last':settings.last }, function(json) { getmsgsreply(json); }, 'json');
            }
        }

        function appendmsgs(msgs) {
            var html = '';

            // Create all html.
            var $b = $this.find('div.b');
            $.each(msgs, function(i, msg) {
                // Check that the message is really not present yet.
                if ($b.find('[ref="' + msg.t + '"]').length == 0) {
                    // Add it to the html.
                    html += '<p class="' + (msg.u == settings.me.id ? 'me' : 'them') + '" ref="' + msg.t + '"><span>' + (msg.u == settings.me.id ? settings.me.chat : settings.them.chat) + '</span>' + msg.m + '</p>';
                }
            });

            // Append it to DOM and scroll down.
            $this.find('div.b').append(html);
            sh = $this.find('div.b').prop('scrollHeight');
            $this.find('div.b').prop({ scrollTop:sh });
        }
        
        function getmsgsreply(json) {
            if (json.success) {
                appendmsgs(json.msgs);
                if (settings.auto) {
                    settings.setTimeoutId = setTimeout(getmsgs, settings.interval);
                }
            } else {
                $($this).find('div.t').html('ERROR');
                $($this).find('p.initmsg').html(json.error);
            }
        }
        
        // --------------------

        // Loop through all selected DOM objects. Should be possible 
        // to run more than one chatbox per window, but not tried.
        return this.each(function() {
            $this = $(this);
            
            // DOM: t=title, b=body, n=input
            $this.html('<div class="t"><span>' + settings.title + '</span></div><div class="b"><p class="initmsg">' + settings.initmsg + '<p></div> <div class="n"><form ><textarea ></textarea><button >&raquo;</button ></form></div>').find('textarea').focus();

            $textarea = $this.find('div.n > form > textarea');
            $button = $this.find('div.n > form > button');

            // Toggle chat popup/popdown.
            $this.on('click.popupchat', 'div.t', function(e) {
                e.preventDefault();

                if ($this.hasClass('closed')) {
                    // Switch to fast interval and open the chat box.
                    settings.interval = settings.fastinterval;
                    clearTimeout(settings.setTimeoutId);
                    settings.setTimeoutId = setTimeout(getmsgs, 100);
                    $this.removeClass('closed').addClass('opened');
                    $textarea.focus();
                } else {
                    // Switch to slow interval and minimize the chatbox.
                    settings.interval = settings.slowinterval;
                    $this.removeClass('opened').addClass('closed');
                    clearTimeout(settings.setTimeoutId);
                    settings.setTimeoutId = setTimeout(getmsgs, settings.interval);
                }
            });

            // Capture chat input
            $this.on('click.popupchat', 'div.n > form > button', function(e) {
                e.preventDefault();

                var textval = $.trim($textarea.val());
                if (textval.length < 1) return false;
                if (textval.length > settings.maxlength) textval = $.substring(0, settings.maxlength);

                // Remove text from textarea and focus it
                $textarea.val('').focus();

                // Prevent the next regular GET poll
                clearTimeout(settings.setTimeoutId);

                // Find the last massage timestamp.
                settings.last = $this.find('div.b > p:last-child').attr('ref');
                if (typeof settings.last == 'undefined') settings.last = 0;

                // Send text to server and sync 
                $.post(settings.api, { 'me':settings.me.id, 'them':settings.them.id, 'msg':textval, 'last':settings.last, 'csrf':settings.csrf }, function(json) { getmsgsreply(json); }, 'json');
            });

            // Check for too long text field input.
            $textarea.on('keypress', function(e) {
                if ($textarea.val().length >= settings.maxlength) {
                    e.preventDefault();
                }
                if (e.keyCode == 13) {
                    e.preventDefault();
                    e.stopPropagation();
                    $this.find('div.n > form > button').trigger('click');
                }
            });

            // Poll the server for msgs.
            getmsgs();
        });

    };

})( jQuery );    
