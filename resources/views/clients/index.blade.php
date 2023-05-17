@extends("layouts.app")

@section("content")
    <nav class="flex p-4">
        <router-link 
            to="/" 
            class="text-xl text-green-600 mx-3 p-2"
            active-class="font-bold"
            exact
        >List of Clients</router-link>
        <router-link 
            to="/create" 
            class="text-xl text-green-600 mx-3 p-2"
            active-class="font-bold"
            exact
        >Add Client</router-link>
    </nav>
    <div class="flex justify-center mt-12">
        <router-view></router-view>
    </div>
@endsection