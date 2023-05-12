<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Geekseat Witch Saga: Return of The Coder!
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Create form -->
                    <form id="form">
                        <!-- Form header -->
                        <div class="flex flex-row my-2 border-b-2">
                            <div class="basis-1/3">Person</div>
                            <div class="basis-1/3">
                                Age Of Death
                            </div>
                            <div class="basis-1/3">
                                Year Of Death
                            </div>
                            <div class="basis-1/3">
                            </div>
                        </div>

                        <!-- Form person 1 default -->
                        <div class="flex flex-row my-2">
                            <div class="basis-1/3">Person 1</div>
                            <div class="basis-1/3">
                                <input type="number" name="age[]" class="rounded text-pink-500 w-full" required />
                            </div>
                            <div class="basis-1/3">
                                <input type="number" name="year[]" class="rounded text-pink-500 w-full" required />
                            </div>
                            <div class="basis-1/3">
                            </div>
                        </div>
                        <!-- person 1 default error message div -->
                        <div class="my-2 errorMessage border-b-2" id="errorMessage_1">
                        </div>

                        <!-- Form person 2 default -->
                        <div class="flex flex-row my-2">
                            <div class="basis-1/3">Person 2</div>
                            <div class="basis-1/3">
                                <input type="number" name="age[]" class="rounded text-pink-500 w-full" required />
                            </div>
                            <div class="basis-1/3">
                                <input type="number" name="year[]" class="rounded text-pink-500 w-full" required />
                            </div>
                            <div class="basis-1/3">
                            </div>
                        </div>
                        <!-- person 2 default error message div -->
                        <div class="my-2 errorMessage border-b-2" id="errorMessage_2">
                        </div>

                        <!-- span for dynamic added person -->
                        <span id="appended-clone"></span>

                        <!-- button for add and submit-->
                        <div class="flex flex-row my-2">
                            <div class="basis-1/2"></div>
                            <div class="basis-1/2">
                                <button type="button" id="add_person" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded btn-add">Add Person</button>
                                <input type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded btn-submit" value="Submit"/>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Result is here -->
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <span id="result"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- all the jquery script -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
    <script type="text/javascript">
        //variable
        var no=3; //start from 3 because the next number after minimum of 2 person required

        //function to add more person form
        $('#add_person').click(function(){
            //create clone html to show after button click
            var clone='<div class="flex flex-row my-2" id="person_'+no+'">'
                +'<div class="basis-1/3">Person '+no+'</div>'
                +'<div class="basis-1/3">'
                    +'<input type="number" name="age[]" class="rounded text-pink-500 w-full" required />'
                +'</div>'
                +'<div class="basis-1/3">'
                    +'<input type="number" name="year[]" class="rounded text-pink-500 w-full" required />'
                +'</div>'
                +'<div class="basis-1/3">'
                    +'<button type="button" id="'+no+'" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded btn_remove">X Remove</button>'
                +'</div>'
            +'</div>'
            +'<div class="my-2 errorMessage border-b-2" id="errorMessage_'+no+'">'
            +'</div>'

            //append the html to the bottom of last person input
            $('#appended-clone').append(clone);
            //add no for numbering people and id
            no++;
        });

        // function to remove the input person form
        $(document).on('click', '.btn_remove', function(){
            var button_id = $(this).attr("id");
            $('#person_'+button_id+'').remove();
        });

        //ajax setup required to send form through csrf
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //btn submit function to call ajax and get the result
        $('#form').submit(function (e) {
            e.preventDefault();
            //serialize the form
            var data = $('#form').serialize();
            //call the ajax
            $.ajax({
                type:'POST',
                url:"{{ route('count') }}",
                data: data,
                success:function(data){
                    $('#result').html(""); // reset the result html to empty before appended
                    $('.errorMessage').html(""); // reset the result html to empty before appended

                    //if there is not valid data then show the error
                    if(data.status == "notvalid"){
                        //loop on each error message to show on the person error
                        $.each(data.errors, function(key,error) {
                            var errHtml='<div class="flex flex-row my-2">'
                            +'<div class="basis-1/4"></div>'
                            +'<div class="basis-2/3"><span class="text-red-400">'+error.msg+'</span></div>'
                            +'</div>';
                            $('#errorMessage_'+error.index).append(errHtml); //append the messagge to bottom of person form
                        });
                    }

                    //if status is success show the result
                    if(data.status=="success"){
                        var result='<div class="flex flex-row my-2"><h1 class="text-center">Average is '+data.avg+'</h1></div>';
                    }else{
                        var result='<div class="flex flex-row my-2"><h1 class="text-center">-1</h1></div>';
                    }
                    $('#result').append(result); //append the result to bottom page
                }
            });
        });
</script>
</x-app-layout>
