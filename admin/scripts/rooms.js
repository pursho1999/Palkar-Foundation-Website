        let add_room_form = document.getElementById('add_room_form');
        add_room_form.addEventListener('submit', function (e) {
            e.preventDefault();
            add_room();
        });


        function add_room()
        {
        let data = new FormData();
        data.append('add_room', ' ');
        data.append('name', add_room_form.elements['name'].value);
        data.append('area', add_room_form.elements['area'].value);
        data.append('price', add_room_form.elements['price'].value);
        data.append('quantity', add_room_form.elements['quantity'].value);
        data.append('adult', add_room_form.elements['adult'].value);
        data.append('children', add_room_form.elements['children'].value);
        data.append('description', add_room_form.elements['description'].value);

        let features = [];
        add_room_form.elements['features'].forEach(el =>{
            if(el.checked)
            {
                features.push(el.value);
            }
        });

        let facilities = [];
        add_room_form.elements['facilities'].forEach(el =>{
            if(el.checked)
            {
                facilities.push(el.value);
            }
        });

        data.append('features', JSON.stringify(features));
        data.append('facilities', JSON.stringify(facilities));

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/rooms.php", true);

        xhr.onload = function () {
            console.log(this.responseText);

            var myModal = document.getElementById('add-room');
            var modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();

            if (this.responseText.trim() === '1') {  // Corrected the condition
                alert('success', 'New Room added!');
                add_room_form.reset();
            // add_room();
                get_all_rooms();
            } else {
                alert('error', 'Server Down!');
            }
        }
        xhr.send(data);
        }


        function get_all_rooms() 
        {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/rooms.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {
        document.getElementById('room-data').innerHTML = this.responseText;
        }

        xhr.send('get_all_rooms=true');
        }

        let edit_room_form = document.getElementById('edit_room_form');

        function edit_details(id) 
        {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/rooms.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () 
        {
            let data=(JSON.parse(this.responseText));
            edit_room_form.elements['name'].value=data.roomdata.name;
            edit_room_form.elements['area'].value=data.roomdata.area;
            edit_room_form.elements['price'].value=data.roomdata.price;
            edit_room_form.elements['quantity'].value=data.roomdata.quantity;
            edit_room_form.elements['adult'].value=data.roomdata.adult;
            edit_room_form.elements['children'].value=data.roomdata.children;
            edit_room_form.elements['description'].value=data.roomdata.description;
            edit_room_form.elements['room_id'].value=data.roomdata.id;

            edit_room_form.elements['features'].forEach(el =>{
            if(data.features.includes(Number(el.value)))
            {
                el.checked=true
            }
            });
            
            edit_room_form.elements['facilities'].forEach(el =>{
            if(data.facilities.includes(Number(el.value)))
            {
                el.checked=true
            }
            });

        }
        xhr.send('get_room='+id);
        }

            edit_room_form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    submit_edit_room();
                });



                function submit_edit_room()
        {
        let data = new FormData();
        data.append('edit_room', ' ');
        data.append('room_id',edit_room_form.elements['room_id'].value);
        data.append('name', edit_room_form.elements['name'].value);
        data.append('area', edit_room_form.elements['area'].value);
        data.append('price', edit_room_form.elements['price'].value);
        data.append('quantity', edit_room_form.elements['quantity'].value);
        data.append('adult', edit_room_form.elements['adult'].value);
        data.append('children', edit_room_form.elements['children'].value);
        data.append('description', edit_room_form.elements['description'].value);

        let features = [];
        edit_room_form.elements['features'].forEach(el =>{
            if(el.checked)
            {
                features.push(el.value);
            }
        });

        let facilities = [];
        edit_room_form.elements['facilities'].forEach(el =>{
            if(el.checked)
            {
                facilities.push(el.value);
            }
        });

        data.append('features', JSON.stringify(features));
        data.append('facilities', JSON.stringify(facilities));

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/rooms.php", true);

        xhr.onload = function () {
            //console.log(this.responseText);

            var myModal = document.getElementById('edit-room');
            var modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();

            if (this.responseText.trim() === '1') {  // Corrected the condition
                alert('success', 'Room Data edited!');
                edit_room_form.reset();
            // add_room();
                get_all_rooms();
            } else {
                alert('error', 'Server Down!');
            }
        }
        xhr.send(data);
        }

        function toggleStatus(id,val)
        {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/rooms.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () 
        {
        if(this.responseText==1)
        {
        alert('success','Status Toggled!');
        get_all_rooms();
        }
        else
        {
        alert('error','Server Down!');
        }
        }

        xhr.send('toggleStatus='+id+'&value='+val);  // Send a proper payload
        }



        let add_images_form =document.getElementById('add_images_form');
        add_images_form.addEventListener('submit',function(e)
        {
        e.preventDefault();
        add_image();
        });

        function add_image() 
        {
        let data = new FormData();
        data.append('image', add_images_form.elements['image'].files[0]);//kitti bhi image seleect karo 1st file hi upload hogi
        data.append('room_id', add_images_form.elements['room_id'].value); 
        data.append('add_image', ' ');

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/rooms.php", true);



        xhr.onload = function () 
        {
        //console.log(this.responseText);

        if (this.responseText === 'inv_img') {
        alert('error', 'Only JPG, WEBP or PNG images are allowed!','image-alert');
        } 
        else if (this.responseText === 'inv_size') {
        alert('error', 'Images should be less than 2MB','image-alert');
        } 
        else if (this.responseText === 'upd_failed') {
        alert('error', 'Image upload failed. Server Down!');
        } 
        else {
        alert('success', 'New Image added!','image-alert');
        room_images(add_images_form.elements['room_id'].value,document.querySelector("#room-images .modal-title").innerText);
        add_images_form.reset();

        //get_carousel();
        }
        };


        xhr.send(data);
        }



        function room_images(id, rname) 
        {
        document.querySelector("#room-images .modal-title").innerText = rname;
        add_images_form.elements['room_id'].value = id;
        add_images_form.elements['image'].value = '';

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/rooms.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () 
        {
        document.getElementById('room-image-data').innerHTML=this.responseText;
        }

        xhr.send('get_room_images=' + id);


        }

        function rem_image(img_id, room_id) {
        let data = new FormData();
        data.append('image_id', img_id);
        data.append('room_id', room_id);
        data.append('rem_image', '');

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/rooms.php", true);

        xhr.onload = function () {
        //console.log('Server response:', this.responseText);

        if (this.responseText === '1') {
        alert('success', 'Image Removed!', 'image-alert');
        room_images(room_id, document.querySelector("#room-images .modal-title").innerText);

        } else {
        alert('error', 'Cannot Delete The Image!', 'image-alert');
        }

        }

        xhr.send(data);
        }

        function thumb_image(img_id, room_id) {
        let data = new FormData();

        data.append('image_id', img_id);
        data.append('room_id', room_id);
        data.append('thumb_image', '');


        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/rooms.php", true);

        xhr.onload = function () {

        if (this.responseText == 1) {
        alert('success', 'Room Thumbnail Changed !', 'image-alert');
        room_images(room_id, document.querySelector("#room-images .modal-title").innerText);
        }

        else {
        alert('error', 'Thumbnail update failed!', 'image-alert');
        }
        }
        xhr.send(data);
        }

        function remove_room(room_id) 
        {

        if(confirm("Are you Sure, you want to delete this room ?"))
        {
        let data = new FormData();
        data.append('room_id',room_id);
        data.append('remove_room', '');

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/rooms.php", true);

        xhr.onload = function () {

        if (this.responseText == 1) {
        alert('success', 'Room Successfully removed!');
        get_all_rooms();
        }

        else {
        alert('error', 'Room failed to remove!');
        }
        }
        xhr.send(data);
        }

        }

        window.onload = function () {
        get_all_rooms();

        }

