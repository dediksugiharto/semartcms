@extends('layouts.app')

@section('title', 'Manage {display_name}')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Manage Books</h1>
    <div class="section-header-button">
        <a href="{{ route('books.create')}}" class="btn btn-primary btn-icon icon-right">Create New <i class="fas fa-plus"></i></a>
    </div>
  </div>
  <div class="section-body">
    @alert
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h4>All Books</h4>
                </div>
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between p-3">
                        <div>
                            <button class="btn btn-secondary">Delete Selected</button>
                        </div>
                            <div class="md-form ml-3 flex-grow-1 mr-3 mb-3">
                                <input class="form-control" type="text" placeholder="Search" aria-label="Search">
                            </div>
                        <div>
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Sort by
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                  <a class="dropdown-item" href="#">Asc</a>
                                  <a class="dropdown-item" href="#">Desc</a>
                                </div>
                              </div>
                        </div>
                    </div>
                    <div class="table-responsive table-invoice">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th>No</th><th> Title</th>
<th> orders</th>

                                </tr>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach($books as $book)
                                <tr>
                                    <td class="text-center align-middle"><input type="checkbox" class="form-check-input" id="checkbox_$books" name="cb_$books[]"></td>
                                    <td>{{ str_limit($book->title, $limit = 50, $end ="...") }}</td>
<td><button type="button" class="btn btn-info" id="btnorders" data-relation ="orders" onclick="showRelation({{ $book->id }}, 'book','orders','belongsToMany')">Show orders</button></td>

                                    <td class="text-right">
                                        <a class="btn btn-primary" href="{{ route('books.edit', $book->id) }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @deletebutton([
                                            'id' => $book->id,
                                            'route' => route('books.destroy', $book->id)
                                        ])
                                            <i class="fa fa-trash"></i>
                                        @enddeletebutton
                                    </td>
                                </tr>
                                @php
                                    $no++;
                                @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $books->links() }}
                </div>
            </div>
        </div>
    </div>
  </div>
</section>
<div class="modal fade mt-5" id="relationModal" tabindex="-1" role="dialog" aria-labelledby="relationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="relationModalLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div id="list-of-data"></div>
        </div>
        <div class="modal-footer">
        </div>
      </div>
    </div>
  </div>
    </section>
@endsection

@push('scripts')
<script>
    function showRelation(record_id, cm_name, target_name, modifier){
        $('#relationModal').modal({"backdrop" : false});
        
        try {
            $.ajax({
                url: '{{ route('content_model.load_related_model_data') }}',
                dataType: 'json',
                type: 'POST',
                data: {
                    "targetName" : target_name,
                    "cmName" : cm_name,
                    "recordId" : record_id,
                    "modifier" : modifier,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {    
                    if(res){
                        if(modifier == "belongsTo" || modifier == "hasOne"){
                            singleData(res, target_name);
                        }else{
                            manyData(res, target_name);
                        }
                    }else{
                        $('#list-of-data').html("No data ");
                    }
                },
                error: function(x, e) {
                    $('#relationModalLabel').html("<h5>Error</h5>");
                    $('#list-of-data').html("No data<br>"+x.responseJSON.message);
                }
            });
            
        } catch (error) {
            console.log(error);
        }

    }

    function singleData(data, target_name){
        html_element = '<table class="table">';

        Object.keys(data)
            .forEach(function eachKey(key) { 
                html_element += `
                    <tr>
                        <td class="font-weight-bold">${ key }</td>
                        <td>${ data[key] }</td>
                    </tr>
                `;
            });

        html_element += '</table>';

        $('#relationModalLabel').html("<h5>"+target_name+"</h5>");
        $('#list-of-data').html(html_element);

    }
    function manyData(data, target_name){

    }
</script>
@endpush
