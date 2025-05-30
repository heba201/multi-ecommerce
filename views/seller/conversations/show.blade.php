@extends('seller.layouts.app')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
            @error("file") 
          <div class="alert alert-danger print-error-msg">
            <ul class="m-0"><li>    
            <span>{{$message}}</span>
                            </li></ul>
                        </div>
                        @enderror

<div class="col-lg-12" id="messages">
@inclue('front.includes.messages',$conversation)
</div>
@endsection

@push('js')
    <script type="text/javascript">
    function refresh_messages(){
        $.post('{{ route('conversations.refresh') }}', {_token:'{{ @csrf_token() }}', id:'{{ encrypt($conversation->id) }}'}, function(data){
            $('#messages').html(data);
        })
    }

    refresh_messages(); // This will run on page load
    setInterval(function(){
        refresh_messages() // this will run after every 50 seconds
    }, 50000);
    </script>
    
    
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
                            url: "{{route('conversations.uploadvoice')}}",
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
@endpush
