<script>
    document.addEventListener('DOMContentLoaded', function () {
        let oldCount = 0;

        function fetchNotifications() {
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

                        htmx.ajax('GET', '{{ route('messageBubble', ['sender_id' => $contact]) }}', {
                            target: '#messages-container',
                            swap: 'innerHTML'
                        });

                        htmx.ajax('GET', '{{ route('admin.messenger-contacts-refresh') }}', {
                            target: '#messsages-contact-list',
                            swap: 'innerHTML'
                        });

                    } else if (newCount < oldCount) {

                        oldCount = newCount;

                        htmx.ajax('GET', '{{ route('messageBubble', ['sender_id' => $contact]) }}', {
                            target: '#messages-container',
                            swap: 'innerHTML'
                        });

                        htmx.ajax('GET', '{{ route('admin.messenger-contacts-refresh') }}', {
                            target: '#messsages-contact-list',
                            swap: 'innerHTML'
                        });

                    }
                })
                .catch((error) => {
                    console.error('Fetch error:', error);
                });
        }

        fetchNotifications();

        setInterval(fetchNotifications, 8000);

    });
</script>