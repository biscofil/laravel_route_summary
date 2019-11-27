<!DOCTYPE html>
<html lang="en">

<head>

    <title>ROUTES</title>

    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>

</head>

<body>


<div class="container-fluid">
    <div id="app">
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.2.6/vue.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.16.1/axios.min.js"></script>

<script>

    document.addEventListener('DOMContentLoaded', function () {

        new Vue({
            el: '#app',

            mounted() {
                let self = this;
                axios.get('./routes.json')
                    .then(function (response) {
                        self.routes = response.data;
                    })
            },

            data: {
                routes: [],
            },

            template: `
<table class="table table-condensed">

    <thead>
        <tr>
            <th>Uri</th>
            <th>Name</th>
            <th>Methods</th>
            <th>Controllers</th>
            <th>Function</th>
            <th>Parameters</th>
            <th>Middleware</th>
        </tr>
    </thead>

    <tbody>
        <tr v-for="route in routes">

            <td>
                {{route.uri}}
            </td>

            <td>
                {{route.name}}
            </td>

            <td>
                <div v-for="method in route.methods" :class="['badge', 'badge-' + method]">
                    {{method}}
                </div>
            </td>

            <td>
                {{route.controller}}
            </td>

            <td>
                {{route.controller_method}}
            </td>

            <td>
                <ul>
                    <li v-for="parameter_type,parameter_name in route.parameters">
                            {{parameter_type}}
                            <b>{{parameter_name}}</b>
                        </span>
                    </li>
                </ul>
            </td>

            <td>
                <ul>
                    <li v-for="middleware in route.middleware">
                        <span v-if="!middleware.hasOwnProperty('params')">{{ middleware}}</span>
                        <span v-else>
                            <b>{{middleware.middleware}}</b>
                            (<span v-for="parameter in middleware.params">{{parameter}},</span>)
                        </span>
                    </li>
                </ul>
            </td>
        </tr>
    </tbody>

</table>`

        });

    });
</script>

<style>

    .badge-GET, .badge-HEAD {
        color: #fff;
        background-color: #1c7430;
    }

    .badge-DELETE {
        color: #fff;
        background-color: #9f191f;
    }

    .badge-POST {
        color: #fff;
        background-color: #0b4d75;
    }

    .badge-PUT {
        color: #fff;
        background-color: #510bc4;
    }


</style>


</body>

</html>
