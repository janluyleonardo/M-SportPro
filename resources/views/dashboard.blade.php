<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                <i class="bi bi-images text-xl"></i>
            </div>
            <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
                {{ __('Galería del Club') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Sección de Fotos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="p-6 sm:px-8 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="bi bi-camera-fill text-blue-500 mr-3 text-2xl"></i> 
                        {{__('Galery_photos')}}
                    </h3>
                    <p class="text-sm text-gray-500 mt-1 ml-9">{{__('Facebook_photos_subtitle')}}</p>
                </div>
                <div class="p-6 sm:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Foto 1 -->
                        <div class="rounded-xl overflow-hidden bg-gray-50 border border-gray-200 shadow-inner p-2 h-[500px] w-full flex justify-center">
                            <iframe src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2Fclubdeportivojackelinefs%2Fposts%2Fpfbid013H65AGi1JD5Z4DCV1My22yBhKUD6ZGhwKitJPKHiMD2oN4eZ5qqxi8mNhKF8MWvl&show_text=true&width=500" width="100%" height="100%" style="border:none;overflow:hidden; max-width: 500px;" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                        </div>
                        <!-- Foto 2 -->
                        <div class="rounded-xl overflow-hidden bg-gray-50 border border-gray-200 shadow-inner p-2 h-[500px] w-full flex justify-center">
                            <iframe src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2Fclubdeportivojackelinefs%2Fposts%2Fpfbid02ccup5Yqu3Z8EQVYc9eDQtwheLnCUdBi1Wxs98hs3fRhTvUZFqUG4XVaMg196oSzpl&show_text=true&width=500" width="100%" height="100%" style="border:none;overflow:hidden; max-width: 500px;" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                        </div>
                        <!-- Foto 3 (Restaurada) -->
                        <div class="rounded-xl overflow-hidden bg-gray-50 border border-gray-200 shadow-inner p-2 h-[500px] w-full flex justify-center">
                            <iframe src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2Fclubdeportivojackelinefs%2Fposts%2Fpfbid02QzDD7U6FaR1PkZQKiMURcSSqWEuGUzkqhuAjXfiKz7obB7ipi516efKyC49M8gw6l&show_text=true&width=500" width="100%" height="100%" style="border:none;overflow:hidden; max-width: 500px;" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                        </div>
                        <!-- Foto 4 (Restaurada) -->
                        <div class="rounded-xl overflow-hidden bg-gray-50 border border-gray-200 shadow-inner p-2 h-[500px] w-full flex justify-center">
                            <iframe src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2Fclubdeportivojackelinefs%2Fposts%2Fpfbid02Eaw94hJ7RcpEDcLibcs2Sehyo6sJuB3aAwQhncEXRCRKducUjjniWyws9nmUxrFNl&show_text=true&width=500" width="100%" height="100%" style="border:none;overflow:hidden; max-width: 500px;" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Videos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="p-6 sm:px-8 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="bi bi-play-btn-fill text-red-500 mr-3 text-2xl"></i> 
                        {{__('Galery_videos')}}
                    </h3>
                    <p class="text-sm text-gray-500 mt-1 ml-9">{{__('Facebook_videos_subtitle')}}</p>
                </div>
                <div class="p-6 sm:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Video 1 -->
                        <div class="rounded-xl overflow-hidden bg-gray-50 border border-gray-200 shadow-inner p-2 h-[350px] w-full flex justify-center">
                            <iframe src="https://www.facebook.com/plugins/video.php?height=314&href=https%3A%2F%2Fwww.facebook.com%2Fclubdeportivojackelinefs%2Fvideos%2F393651932703844%2F&show_text=false&width=560&t=0" width="100%" height="100%" style="border:none;overflow:hidden; max-width: 560px;" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"></iframe>
                        </div>
                        <!-- Video 2 -->
                        <div class="rounded-xl overflow-hidden bg-gray-50 border border-gray-200 shadow-inner p-2 h-[350px] w-full flex justify-center">
                            <iframe src="https://www.facebook.com/plugins/video.php?height=314&href=https%3A%2F%2Fwww.facebook.com%2Fclubdeportivojackelinefs%2Fvideos%2F1243418113173667%2F&show_text=false&width=560&t=0" width="100%" height="100%" style="border:none;overflow:hidden; max-width: 560px;" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"></iframe>
                        </div>
                        <!-- Video 3 (Restaurado) -->
                        <div class="rounded-xl overflow-hidden bg-gray-50 border border-gray-200 shadow-inner p-2 h-[350px] w-full flex justify-center">
                            <iframe src="https://www.facebook.com/plugins/video.php?height=308&href=https%3A%2F%2Fwww.facebook.com%2Fclubdeportivojackelinefs%2Fvideos%2F360486009096274%2F&show_text=false&width=560&t=0" width="100%" height="100%" style="border:none;overflow:hidden; max-width: 560px;" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"></iframe>
                        </div>
                        <!-- Video 4 (Restaurado) -->
                        <div class="rounded-xl overflow-hidden bg-gray-50 border border-gray-200 shadow-inner p-2 h-[350px] w-full flex justify-center">
                            <iframe src="https://www.facebook.com/plugins/video.php?height=314&href=https%3A%2F%2Fwww.facebook.com%2Fclubdeportivojackelinefs%2Fvideos%2F352430906903237%2F&show_text=false&width=560&t=0" width="100%" height="100%" style="border:none;overflow:hidden; max-width: 560px;" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"></iframe>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Instagram (Restaurada) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="p-6 sm:px-8 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="bi bi-instagram text-pink-600 mr-3 text-2xl"></i> 
                        {{__('Instagram')}}
                    </h3>
                    <p class="text-sm text-gray-500 mt-1 ml-9">{{__('Instagram_subtitle')}}</p>
                </div>
                <div class="p-6 sm:p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 justify-items-center">
                        <!-- Instagram 1 -->
                        <div class="w-full flex justify-center overflow-hidden rounded-xl border border-gray-100">
                            <blockquote class="instagram-media" data-instgrm-captioned data-instgrm-permalink="https://www.instagram.com/p/CoDPVk2P2Wd/?utm_source=ig_embed&amp;utm_campaign=loading" data-instgrm-version="14" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);"></blockquote>
                        </div>
                        <!-- Instagram 2 -->
                        <div class="w-full flex justify-center overflow-hidden rounded-xl border border-gray-100">
                            <blockquote class="instagram-media" data-instgrm-captioned data-instgrm-permalink="https://www.instagram.com/p/CnxGOljuGkJ/?utm_source=ig_embed&amp;utm_campaign=loading" data-instgrm-version="14" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);"></blockquote>
                        </div>
                        <!-- Instagram 3 -->
                        <div class="w-full flex justify-center overflow-hidden rounded-xl border border-gray-100">
                            <blockquote class="instagram-media" data-instgrm-captioned data-instgrm-permalink="https://www.instagram.com/p/C1iB8UKrdpQ/?utm_source=ig_embed&amp;utm_campaign=loading" data-instgrm-version="14" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);"></blockquote>
                        </div>
                    </div>
                </div>
                <!-- Instagram Script -->
                <script async src="//www.instagram.com/embed.js"></script>
            </div>

        </div>
    </div>
</x-app-layout>
