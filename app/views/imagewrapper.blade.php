<!doctype html><html><head><style>
body { min-width:100%; min-height:100%;

@if (Input::has('url'))
    background-image:url({{ Input::get('url') }});
    background-size:cover;
@endif

}
</style></head><body></body></html>
