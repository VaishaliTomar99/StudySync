
<!DOCTYPE html>
<html>

<head>

    <title>Notes Summarizer</title>

    <style>

        body{

            background:
            linear-gradient(135deg,#5f5fff,#7f4dff);

            font-family: Arial;

            padding: 40px;

            color: white;

        }

        textarea{

            width: 100%;
            height: 300px;

            border-radius: 20px;

            padding: 20px;

            font-size: 20px;

            border: none;

        }

        button{

            margin-top: 20px;

            padding: 15px 30px;

            border: none;

            border-radius: 15px;

            background: white;

            color: #5f5fff;

            font-size: 22px;

            cursor: pointer;

        }

        #result{

            margin-top: 30px;

            background: white;

            color: black;

            padding: 25px;

            border-radius: 20px;

            min-height: 150px;

        }

    </style>

</head>

<body>

<h1>📝 Notes Summarizer</h1>

<textarea
id="notes"
placeholder="Paste your notes here..."></textarea>

<br><br>

<input type="file" id="file">

<br>

<button onclick="summarizeNotes()">
Summarize Notes
</button>

<div id="result"></div>


<script>

async function summarizeNotes(){

    let notes =
    document.getElementById("notes").value;

    let file =
    document.getElementById("file").files[0];

    let formData =
    new FormData();

    formData.append("notes", notes);

    if(file){

        formData.append("file", file);

    }

    document.getElementById("result").innerHTML =
    "⏳ Summarizing...";


    try{

        let response =
        await fetch("summarize-notes.php",{

            method:"POST",

            body:formData

        });

        let data =
        await response.json();

        document.getElementById("result").innerHTML =

        data.summary;

    }

    catch(error){

        document.getElementById("result").innerHTML =

        "❌ Error summarizing notes";

    }

}

</script>

</body>

</html>