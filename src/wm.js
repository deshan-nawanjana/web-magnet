/*
    WebMagnet by Deshan Nawanjana
    https://github.com/deshan-nawanjana/web-magnet
*/

let WebMagnet = function(url, obj, che = true) {
    // find pages on url
    let tmp = url.match(/{\d+:\d+}/)
    // store values
    if(tmp !== null) {
        // multi page mode
        let rng = tmp[0].substr(1, tmp[0].length - 2).split(':')
        this.url = url.replace(tmp[0], '{#}')
        this.crr = parseInt(rng[0])
        this.end = parseInt(rng[1])
    } else {
        // single page mode
        this.crr = 0
        this.end = 0
        this.url = url
    }
    this.obj = obj
    this.che = che
    this.out = []
    // scraping start function
    this.start = async prg => {
        // for each page
        for(let i = this.crr; i <= this.end; i++) {
            let url = this.url.replace('{#}', i)
            let che = this.che ? '&cache' : ''
            await fetch('src/wm.php?url=' + encodeURIComponent(url) + che, {
                method : 'GET'
            })
            .then(res => res.text())
            .then(res => {
                this.scrape(res)
                if(typeof prg === 'function') { prg(url) }
            })
        }
        return this.out
    }
    this.scrape = htm => {
        // create document
        let doc = document.implementation.createHTMLDocument('TEST')
        // append response
        doc.documentElement.innerHTML = htm
        // recursive to store all selectors
        let arr = {}
        let recArr = obj => {
            Object.values(obj).forEach(val => {
                if(typeof val === 'string') {
                    arr[val] = []
                } else { recArr(val); }
            })
        }
        // store all selectors in arr
        recArr(this.obj)
        // get each selector inner Text
        let max = 0
        Object.keys(arr).forEach(css => {
            Array.from(doc.querySelectorAll(css)).forEach(e => {
                arr[css].push(e.innerText)
            })
            // get maximum length of results
            if(arr[css].length > max) { max = arr[css].length }
        })
        // recursive store outputs in object stucture
        let recOut = (obj, css, val) => {
            Object.keys(obj).forEach(key => {
                if(typeof obj[key] === 'object') {
                    recOut(obj[key], css, val)
                }
                if(typeof obj[key] === 'string' && obj[key] === css) {
                    obj[key] = val
                }
            })
        }
        // build item for final result out
        let out = []
        // each selector result
        for(let i = 0; i < max; i++) {
            // clone object stucture
            let obj = JSON.parse(JSON.stringify(this.obj))
            Object.keys(arr).forEach(css => {
                recOut(obj, css, arr[css][i])
            })
            // store item output
            out.push(obj)
        }
        // push current page output
        this.out.push(out)
    }
}