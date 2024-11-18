<script>
    document.addEventListener('DOMContentLoaded', function () {
        let oldCount = 0;

        function fetchNotifications() {
            fetch('/notifications', {
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


                        document.getElementById('notif-count').innerHTML = `${newCount}`;
                    }

                    oldCount = newCount;
                })
                .catch((error) => {
                    console.error('Fetch error:', error);
                });
        }


        fetchNotifications();


        setInterval(fetchNotifications, 5000);
    });
</script>