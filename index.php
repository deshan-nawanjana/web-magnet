<!DOCTYPE html>
<html>
    <head>
        <title>WebMagnet</title>
        <link rel="shortcut icon" href="./src/wm.png" type="image/png">
        <script src="./src/wm.js"></script>
        <style>
            * {
                font-family: 'Courier New', Courier, monospace;
                outline: none;
                border: none;
                user-select: none;
            }
            body {
                margin: 10px;
                background-color: #3a3a40;
            }
            label {
                display: block;
                padding: 5px 20px;
                line-height: 30px;
                color: #FFF9;
                font-weight: 600;
            }
            input[type="text"] {
                padding: 0px 10px;
                height: 30px;
                width: 700px;
                background-color: #1118;
                color: #FFF9;
            }
            button {
                display: block;
                margin: 10px 20px;
                height: 30px;
                width: 180px;
                font-size: 13px;
                font-weight: 600;
                color: #111E;
                opacity: 0.85;
            }
            button:hover { opacity: 1; }
            button:active { opacity: 0.5; }
            textarea {
                margin: 10px 20px;
                display: block;
                padding: 10px;
                resize: none;
                height: 200px;
                width: 700px;
                background-color: #1118;
                color: #FFF9;
                font-size: 11px;
            }
        </style>
        <script>
            let run = () => {
                // clear last output
                document.querySelector('textarea').value = ''
                // define url with page range {start:end}
                let url = document.querySelectorAll('input')[0].value
                // define object of each item of array with css selectors as values
                let obj = JSON.parse(document.querySelectorAll('input')[1].value)
                // create new magnet
                let mag = new WebMagnet(url, obj, document.querySelectorAll('input')[2].checked)
                // run magnet and get result
                mag.start(prg).then(out => {
                    console.log(out)
                    document.querySelector('textarea').value = JSON.stringify(out)
                })
            }
            // define callback function for progress
            let prg = crr => {
                document.querySelector('textarea').value = crr
            }
            let pop = () => {
                let name = Date.now().toString() + '.json'
                let data = document.querySelector('textarea').value.toString();
                if(data.indexOf('data:') != 0) {
                    data = 'data:text/plain;charset=utf-8,' + encodeURIComponent(data);
                }
                var e = document.createElement('a');
                e.setAttribute('href', data);
                e.setAttribute('download', name);
                e.style.display = 'none';
                var c = document.body;
                if(c == null) { c = document.head; }
                if(c == null) { c = document.documentElement; }
                c.appendChild(e);
                e.click();
                c.removeChild(e);
            };
        </script>
    </head>
    <body>
        <label>
            Scrape URL<br>
            <input type="text" value="https://yts.mx/browse-movies?page={1:10}">
        </label>
        <label>
            Stucture of Output Array Item<br>
            <input type="text" value='{ "name" : ".browse-movie-title" , "year" : ".browse-movie-year" }'>
        </label>
        <label>
            <input type="checkbox" checked> Cache Mode
        </label>
        <button onclick="run()">Run Magnet</button>
        <textarea placeholder="// Output" readonly></textarea>
        <button onclick="pop()">Download JSON</button>
    </body>
</html>