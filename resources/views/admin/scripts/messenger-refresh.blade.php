<script>
    document.addEventListener('DOMContentLoaded', function () {
        let oldCount = 0;

        function fetchMessages() {
            fetch('/messages/{{$contact}}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then((data) => {

                    const newCount = data.length;

                    if (newCount > oldCount) {


                        oldCount = newCount;

                        // First request
                        htmx.ajax('GET', '{{ route('admin.messenger-contacts-refresh') }}', {
                            target: '#messsages-contact-list',
                            swap: 'innerHTML'
                        });

                        // Listen for the completion of the first request and then trigger the second one
                        htmx.on('htmx:afterSwap', function (event) {
                            if (event.target.id === 'messsages-contact-list') {  // Ensure the event is triggered by the right request
                                htmx.ajax('GET', '{{ route('messageBubble', ['sender_id' => $contact]) }}', {
                                    target: '#messages-container',
                                    swap: 'innerHTML'
                                });
                            }
                        });


                    } else if (newCount < oldCount) {

                        oldCount = newCount;


                        // First request
                        htmx.ajax('GET', '{{ route('admin.messenger-contacts-refresh') }}', {
                            target: '#messsages-contact-list',
                            swap: 'innerHTML'
                        });

                        // Listen for the completion of the first request and then trigger the second one
                        htmx.on('htmx:afterSwap', function (event) {
                            if (event.target.id === 'messsages-contact-list') {  // Ensure the event is triggered by the right request
                                htmx.ajax('GET', '{{ route('messageBubble', ['sender_id' => $contact]) }}', {
                                    target: '#messages-container',
                                    swap: 'innerHTML'
                                });
                            }
                        });

                    }
                })
                .catch((error) => {
                    console.error('Fetch error:', error);
                });
        }

        fetchMessages();

        setInterval(fetchMessages, 1000);

    });
</script>