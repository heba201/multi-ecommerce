
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <div class="aiz-titlebar mt-2 mb-4">
        <div class="h6">
            <span>{{ tran('Conversations With ')}}</span>
            @if ($conversation->sender_id == Auth::user()->id && $conversation->receiver->shop != null)
                <a href="{{ route('shop.visit', $conversation->receiver->shop->slug) }}" class="">{{ $conversation->receiver->shop->name }}</a>
            @endif
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title fs-16 fw-600 mb-0">#{{ $conversation->title }}
            (
                {{ tran('Between you and') }}
                @if ($conversation->sender_id == Auth::user()->id)
                    {{ $conversation->receiver->name }}
                @else
                    {{ $conversation->sender->name }}
                @endif
            )
            </h5>
        </div>

        <div class="card-body">
            <ul class="list-group list-group-flush">
                @foreach($conversation->messages as $message)
                    <li class="list-group-item px-0">
                        <div class="media mb-2">
                            <?php
                            if($message->user != null && $message->user->avatar_original !=""){
                                $avatar=asset('assets/images/users/'.$message->user->avatar_original);
                            }else  $avatar=asset('assets/images/users/avatar.png');
                            ?>
                          <img class="avatar avatar-xs mr-3"  src="{{ $avatar }}" >
                          <div class="media-body">
                            <h6 class="mb-0 fw-600">
                                @if ($message->user != null)
                                    {{ $message->user->name }}
                                @endif
                            </h6>
                            <p class="opacity-50">{{$message->created_at}}</p>
                          </div>
                        </div>
                        <p>
                            <?php 
                            $msg_arr=[];
                            if (strpos($message->message, "<br>") !== false) {
                                     $msg_arr=explode("<br>",$message->message);  
                            ?>
                               <a href="<?php echo $msg_arr[0];?>" target="_blank"><?php echo $msg_arr[0];?></a>
                        <br>
                        <?php echo $msg_arr[1];?>
                          <?php
                            }else{
                                echo $message->message;
                            }
                           
                           ?>
                        
                        </p>
                            @if($message->message_photo !="")
                        <img alt="" class="img-thumbnail" src="{{asset('assets/images/messages_photos/'.$message->message_photo)}}" width="200" height="200">
                        <br><br>
                        @endif
                        <p>
                            @if($message->voice_message !="")
                        <audio controls>
                        <source src="{{asset($message->voice_message)}}" type="audio/wav"></audio>
                      @endif
                        </p>
                    </li>
                @endforeach
            </ul>
            <?php
              if(Auth::user()->user_type=="seller"){
                $route=route('conversations.message_store') ;
              }
            if(Auth::user()->user_type=="admin"){
                $route=route('admin.conversations.message_store') ;
            }if(Auth::user()->user_type=="customer"){
                $route= route('customer.conversations.message_store');
            }
            ?>
            <form class="pt-4" action="{{ $route }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                <div class="form-group">
                    <textarea class="form-control" rows="5" name="message" placeholder="{{ tran('Type your reply') }}" required></textarea>
                </div>
               
                 <div class="form-group">
                <label for="formFile" class="form-label">{{ tran('Browse')}}</label>
                    <input class="form-control" type="file" name="file" id="formFile">
                  
                </div>

                <div class="form-group">

                <h4><span id="status">{{ tran('Not recording')}}</span></h4>
                    <div id="controls">
                    <button id="recordButton" onclick="return false;" style="padding:10px;border: none;background: transparent"><i class="fa-solid fa-microphone fa-xl"></i></button>
                        <button  class="btn btn-primary" id="stopButton" style="padding:10px;border: none;background: transparent"  disabled><i class="fa-solid fa-stop fa-xl" style="color:red;"></i></button>
                   <input type="hidden" name="voice" id="voice">
                   
                    </div>
                    <br>
                    <div id="audioContainer"></div>
                </div>
               
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{ tran('Send') }}</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        // Initialize the audio context
        window.AudioContext = window.AudioContext || window.webkitAudioContext;
        var audioContext = new AudioContext();
        var audioChunks = [];
        var mediaRecorder; // Declare mediaRecorder variable

        // Get the record and stop buttons
        var recordButton = document.getElementById('recordButton');
        var stopButton = document.getElementById('stopButton');

        // Add event listeners
        recordButton.addEventListener('click', startRecording);
        stopButton.addEventListener('click', stopRecording);

        // Start recording function
        function startRecording() {
            document.getElementById('audioContainer').innerHTML="";
            navigator.mediaDevices.getUserMedia({ audio: true })
                .then(function(stream) {
                    mediaRecorder = new MediaRecorder(stream); // Assign mediaRecorder variable

                    mediaRecorder.addEventListener('dataavailable', function(e) {
                        audioChunks.push(e.data);
                    });

                    mediaRecorder.addEventListener('stop', function() {
                        var audioBlob = new Blob(audioChunks, { 'type' : 'audio/wav' });

                        var formData = new FormData();
                        formData.append('audio', audioBlob, 'recording.wav');
                        formData.append('_token',"{{ csrf_token() }}");
                        $.ajax({
                            url: "{{route('customer.conversations.uploadvoice')}}",
                            type: 'POST',
                            data:formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                console.log(response);
        displayAudio(response.url);
        console.log(response);
         $("#voice").val(response.file);
     },
        error: function(xhr, status, error) {
         console.error('Error uploading file: ' + error);
                            }
                        });

                        audioChunks = []; // Reset audioChunks array after upload
                    });

                    mediaRecorder.start();

                    // Update UI
                    recordButton.disabled = true;
                    stopButton.disabled = false;
                    document.getElementById('status').innerHTML = 'Recording...';
                })
                .catch(function(err) {
                    console.error('Error accessing microphone: ' + err);
                });
        }

        // Stop recording function
        function stopRecording() {
            mediaRecorder.stop();

            // Update UI
            recordButton.disabled = false;
            stopButton.disabled = true;
       
            document.getElementById('status').innerHTML ='';
            document.getElementById('audioContainer').innerHTML="";
        }

         // Display audio
         function displayAudio(audioPath) {
            
            var audioPlayer = document.createElement('audio');
            audioPlayer.controls = true;
            audioPlayer.src = audioPath;
            document.getElementById('audioContainer').appendChild(audioPlayer);
        }
    </script>