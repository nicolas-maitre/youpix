<html>
    <body>
        <a href="{{route('pictures.index')}}">retour</a>
        <h1>Créer :)2</h1>
        <form id="addForm">
            <input type="text" name="title" required/>
            {{$errors->first('title')}}<br/>
            <input type="file" name="picture" required/>
            {{$errors->first('picture')}}<br/>
            <button type="submit">Envoyer</button>
        </form>
        
        <script>
            var formAttributes = {!! json_encode($formAttributes) !!};
            var formInputs = {!! json_encode($formInputs) !!};
            var storeRoute = "{{route('pictureApiStore')}}";

            
            addForm.addEventListener("submit", onSubmit)
            async function onSubmit(evt){
                evt.preventDefault();
                //get
                var title = addForm.querySelector("[name=title]").value;
                var picture = addForm.querySelector("[name=picture]").files[0];
                //store
                var isOk = await storeImage(picture);
                if(!isOk){
                    console.error("image store error", {isOk});
                    alert("an error ocurred in image store");
                    return;
                }

                var picture = await storePicture(title);
                if(!picture){
                    console.error("picture store error", {picture});
                    alert("an error ocurred in picture store");
                    return;
                }
                //redirect
                window.location = `/pictures/${picture.id}`;
            }
            async function storeImage(picture){
                const formData = new FormData();
                for(var indInput in formInputs){
                    formData.append(indInput, formInputs[indInput]);
                }
                formData.append("file", picture);

                var fetchOptions = {
                    method: formAttributes.method,
                    headers: {
                        //'Content-Type': formAttributes.enctype
                    },
                    body: formData
                }
                const res = await fetch(formAttributes.action, fetchOptions);
                console.log("aaaaaaaaaaa", res);
                if(!res.ok){
                    console.log("error fetch", res);
                    return false;
                }
                return true;
            }
            async function storePicture(title){
                const formData = new FormData();
                formData.append("title", title);
                formData.append("storage_path", formInputs.key);

                var fetchOptions = {
                    method: "POST",
                    headers: {
                        //'Content-Type': formAttributes.enctype
                    },
                    body: formData
                }
                var res = await fetch(storeRoute, fetchOptions);
                if(!res.ok){
                    return false;
                }
                var decoded = await res.json();
                return decoded;
            }
        </script>
    </body>
</html>
