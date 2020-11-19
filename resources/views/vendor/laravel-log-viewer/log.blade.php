@extends('layouts.app')

@section('content')

    <div class="mb-4">
        <h1 class="mb-3">Laravel Log Viewer</h1>

        <div class="list-group small">
            @foreach($folders as $folder)
                <div class="list-group-item">
                    <a href="?f={{ Crypt::encrypt($folder) }}">
                        <span class="fa fa-folder"></span> {{$folder}}
                    </a>
                    @if ($current_folder == $folder)
                        <div class="list-group folder">
                            @foreach($folder_files as $file)
                                <a href="?l={{ Crypt::encrypt($file) }}&f={{ Crypt::encrypt($folder) }}"
                                    class="list-group-item @if ($current_file == $file) llv-active @endif">
                                    {{$file}}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
            @foreach($files as $file)
                <a href="?l={{ Crypt::encrypt($file) }}"
                    class="list-group-item @if ($current_file == $file) llv-active @endif">
                    {{$file}}
                </a>
            @endforeach
        </div>
    </div>

    <div class="table-wrapper">
        @if ($logs === null)
            <div>
                Log file >50M, please download it.
            </div>
        @else
            <table id="table-log" class="table table-striped" data-ordering-index="{{ $standardFormat ? 2 : 0 }}">
                <thead>
                <tr>
                    @if ($standardFormat)
                        <th>Level</th>
                        <th>Context</th>
                        <th>Date</th>
                    @else
                        <th>Line number</th>
                    @endif
                    <th>Content</th>
                </tr>
                </thead>
                <tbody>

                @foreach($logs as $key => $log)
                    <tr data-display="stack{{{$key}}}">
                        @if ($standardFormat)
                            <td class="nowrap text-{{{$log['level_class']}}}">
                                {{$log['level']}}
                            </td>
                            <td class="text">{{$log['context']}}</td>
                        @endif
                        <td class="date small">{{{$log['date']}}}</td>
                        <td class="text small">
                            {{{$log['text']}}}
                            @if (isset($log['in_file']))
                                <br/>{{{$log['in_file']}}}
                            @endif
                            @if ($log['stack'])
                                <div class="stack" id="stack{{{$key}}}"
                                    style="display: none; white-space: pre-wrap;">{{{ trim($log['stack']) }}}
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        @endif

        <div class="mt-3">
            @if($current_file)
                <a href="?dl={{ Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . Crypt::encrypt($current_folder) : '' }}"
                    class="btn btn-sm btn-outline-primary">
                    Download file
                </a>

                <a href="?clean={{ Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . Crypt::encrypt($current_folder) : '' }}"
                    class="btn btn-sm btn-outline-warning">
                    Clean file
                </a>

                <a href="?del={{ Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . Crypt::encrypt($current_folder) : '' }}"
                    class="btn btn-sm btn-outline-danger">
                    Delete file
                </a>

                @if(count($files) > 1)
                    <a href="?delall=true{{ ($current_folder) ? '&f=' . Crypt::encrypt($current_folder) : '' }}"
                        class="btn btn-sm btn-outline-danger">
                        Delete all files
                    </a>
                @endif
            @endif
        </div>
    </div>
@endsection
