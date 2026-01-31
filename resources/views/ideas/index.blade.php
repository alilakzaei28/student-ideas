<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سامانه ایده‌های دانشجویی</title>
    @vite('resources/css/app.css') 
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;700;900&display=swap" rel="stylesheet">
    <style> body { font-family: 'Vazirmatn', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-gray-800">

    <nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-3xl font-black text-teal-600 tracking-tight">سامانه ایده</a>
            
            <div class="flex items-center space-x-4 rtl:space-x-reverse">
                @auth 
                    {{-- بخش نوتیفیکیشن --}}
                    <div class="relative group">
                        <button class="relative p-2 text-gray-500 hover:text-teal-600 transition">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <span class="absolute top-1 right-1 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5 animate-pulse">{{ Auth::user()->unreadNotifications->count() }}</span>
                            @endif
                        </button>
                        
                        <div class="absolute left-0 mt-2 w-80 bg-white border border-gray-200 rounded-xl shadow-2xl hidden group-hover:block z-50 overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b text-sm font-bold text-gray-700 flex justify-between items-center">
                                <span>اعلان‌ها</span>
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <a href="{{ route('notifications.read') }}" class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded hover:bg-blue-200 transition">
                                        ✔ خواندم
                                    </a>
                                @endif
                            </div>
                            <ul class="max-h-60 overflow-y-auto">
                                @forelse(Auth::user()->unreadNotifications as $notification)
                                    <li class="border-b last:border-0 hover:bg-gray-50 transition bg-blue-50/50">
                                        <div class="px-4 py-3 text-sm text-gray-600">
                                            {{ $notification->data['message'] }}
                                            <div class="text-xs text-gray-400 mt-1 flex justify-between">
                                                <span>{{ verta($notification->created_at)->formatDifference() }}</span>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li class="px-4 py-6 text-sm text-gray-400 text-center">هیچ اعلان جدیدی ندارید.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <span class="text-sm text-gray-600 hidden sm:inline-block border-l pl-4 ml-4">{{ Auth::user()->name }}</span>
                    <button data-modal-target="idea-modal" data-modal-toggle="idea-modal" type="button" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-4 rounded-full text-sm shadow-md">+ ثبت ایده</button>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf <button type="submit" class="text-gray-500 hover:text-red-600 text-sm py-2 px-3 rounded-full">خروج</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-teal-600 text-sm py-2 px-3 rounded-full">ورود</a>
                    <a href="{{ route('register') }}" class="bg-slate-200 hover:bg-slate-300 text-gray-700 font-semibold py-2 px-4 rounded-full text-sm">ثبت نام</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if (session('success')) <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg">{{ session('success') }}</div> @endif
        @if ($errors->any()) <div class="p-4 mb-6 text-sm text-red-700 bg-red-100 rounded-lg">خطا در ورودی‌ها</div> @endif

        <div class="mb-8">
            <form action="{{ route('ideas.index') }}" method="GET">
                <div class="relative">
                    <input type="search" name="search" value="{{ request('search') }}" class="block w-full p-4 ps-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-teal-500 focus:border-teal-500" placeholder="جستجو در ایده‌ها..." />
                    <button type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-teal-600 hover:bg-teal-700 font-medium rounded-lg text-sm px-4 py-2">جستجو</button>
                </div>
            </form>
        </div>

        <div class="space-y-6">
            @forelse ($ideas as $idea)
                <div class="bg-white p-6 rounded-xl shadow-xl border-s-4 border-teal-500 flex flex-col md:flex-row justify-between items-start">
                    
                    <div class="flex-grow mb-4 md:mb-0 md:pe-6 w-full">
                        <div class="flex items-start justify-between mb-2">
                             <h3 class="text-xl font-extrabold text-gray-900">{{ $idea->title }}</h3>
                             @if($idea->category)
                                <a href="?category={{ $idea->category->slug }}" class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded hover:bg-indigo-200 transition">{{ $idea->category->name }}</a>
                             @endif
                        </div>
                        
                        <div class="text-xs text-gray-500 mb-3 flex flex-wrap gap-3 items-center">
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                {{ $idea->user->name }}
                            </span>
                            
                            <span class="text-gray-300">|</span>
                            <span class="flex items-center gap-1 text-gray-600">
                                {{ verta($idea->created_at)->format('%d %B %Y') }}
                            </span>
                            
                            @if (Auth::check() && Auth::id() === $idea->user_id)
                                <span class="text-gray-300">|</span>
                                <form action="{{ route('ideas.destroy', $idea) }}" method="POST" onsubmit="return confirm('حذف شود؟');" class="inline">
                                    @csrf @method('DELETE') <button type="submit" class="text-red-500 hover:text-red-700 font-bold">[حذف]</button>
                                </form>
                            @endif
                        </div>

                        <p class="text-gray-600 text-sm leading-relaxed">{{ $idea->description }}</p>
                    </div>
                    
                    <div class="flex flex-col items-center justify-center bg-slate-50 p-3 rounded-lg border border-slate-200 min-w-[80px]">
                        <form action="{{ route('ideas.vote', $idea) }}" method="POST">
                            @csrf <input type="hidden" name="is_upvote" value="1">
                            @php $hasUpvoted = Auth::check() && $idea->votes->where('user_id', Auth::id())->where('is_upvote', true)->count(); @endphp
                            <button type="submit" class="p-1 hover:text-green-600 transition {{ $hasUpvoted ? 'text-green-600 scale-125' : 'text-gray-400' }}">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4l-8 8h6v8h4v-8h6z"/></svg>
                            </button>
                        </form>
                        
                        <span class="text-2xl font-black {{ $idea->score > 0 ? 'text-green-600' : ($idea->score < 0 ? 'text-red-600' : 'text-gray-600') }}">
                            {{ $idea->score }}
                        </span>

                        <form action="{{ route('ideas.vote', $idea) }}" method="POST">
                            @csrf <input type="hidden" name="is_upvote" value="0">
                            @php $hasDownvoted = Auth::check() && $idea->votes->where('user_id', Auth::id())->where('is_upvote', false)->count(); @endphp
                            <button type="submit" class="p-1 hover:text-red-600 transition {{ $hasDownvoted ? 'text-red-600 scale-125' : 'text-gray-400' }}">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 20l8-8h-6v-8h-4v8h-6z"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500">موردی یافت نشد.</p>
            @endforelse
            
            <div class="mt-4">{{ $ideas->links() }}</div>
        </div>
    </main>

    @auth
    <div id="idea-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="p-4 md:p-5">
                    <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">ثبت ایده جدید</h3>
                    <form action="{{ route('ideas.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <select name="category_id" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5">
                            @foreach($categories as $category) <option value="{{ $category->id }}">{{ $category->name }}</option> @endforeach
                        </select>
                        <input type="text" name="title" required placeholder="عنوان ایده" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5">
                        <textarea name="description" rows="4" required placeholder="توضیحات..." class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5"></textarea>
                        <button type="submit" class="w-full text-white bg-teal-700 hover:bg-teal-800 font-medium rounded-lg text-sm px-5 py-2.5">ثبت</button>
                    </form>
                </div>
            </div>
        </div>
    </div> 
    @endauth

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html>