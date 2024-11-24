<script>
    document.addEventListener('DOMContentLoaded', function () {
        let oldCount = 0;

        function fetchMessages() {
            fetch('/messages', {
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
                        document.getElementById('message-count').innerHTML = `${newCount}`;

                        oldCount = newCount;

                        showMessagesLoader();

                        htmx.ajax('GET', '{{ route('admin.contacts-refresh') }}', {
                            target: '#messages-dropdown',
                            swap: 'innerHTML'
                        });

                    } else if (newCount < oldCount) {
                        document.getElementById('message-count').innerHTML = `${newCount}`;

                        oldCount = newCount;

                        showMessagesLoader();

                        htmx.ajax('GET', '{{ route('admin.contacts-refresh') }}', {
                            target: '#messages-dropdown',
                            swap: 'innerHTML'
                        });

                    }
                })
                .catch((error) => {
                    console.error('Fetch error:', error);
                });
        }

        fetchMessages();

        setInterval(fetchMessages, 5000);

        function showMessagesLoader() {
            document.getElementById('messages-loader').classList.remove('hidden');
        }

        function hideMessagesLoader() {
            document.getElementById('messages-loader').classList.add('hidden');
        }

        document.body.addEventListener('htmx:afterRequest', function () {
            hideMessagesLoader();


        });
    });
</script>