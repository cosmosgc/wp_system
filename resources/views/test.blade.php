<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>teste</title>
</head>
<body>
    <button class="teste">click-me</button>
</body>

<script>
    let testElement= document.querySelector(".teste");
    testElement.addEventListener('click',async ()=>{
        const query=await fetch('/getTest');
        const response = await query.json();
        console.log(response);
    })
</script>
</html>