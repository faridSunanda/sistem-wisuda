<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\GroupExport;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

class GroupController extends Controller
{
    public function index()
    {
        return view('admin.master.group.index');
    }

    public function getData(Request $request)
    {
        $data = Group::query()->orderBy('name', 'asc');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function($row) {
                $id = $row->id;
                $baseStyles = 'flex items-center justify-center w-8 h-8 rounded text-white transition-all';
                $btn = '<div class="flex items-center justify-center gap-2">';

                $btn .= '<button class="' . $baseStyles . ' bg-[#435ebe] hover:bg-[#3a52a8]" onclick="lihatData(\''.$id.'\')" title="Lihat">';
                $btn .= '<i class="fas fa-eye"></i>';
                $btn .= '</button>';

                $btn .= '<button class="' . $baseStyles . ' bg-yellow-500 hover:bg-yellow-600" onclick="editData(\''.$id.'\')" title="Edit">';
                $btn .= '<i class="fas fa-pencil-alt"></i>';
                $btn .= '</button>';

                $btn .= '<button class="' . $baseStyles . ' bg-red-500 hover:bg-red-600" onclick="hapusData(\''.$id.'\', this)" title="Hapus">';
                $btn .= '<i class="fas fa-trash"></i>';
                $btn .= '</button>';

                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function exportData(Request $request)
    {
        try {
            $data = Group::query()
                ->select('name', 'keterangan')
                ->orderBy('name', 'asc')
                ->get();

            if ($data->isEmpty()) {
                return response()->json([]);
            }

            return response()->json($data->toArray());

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        return view('admin.master.group.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        Group::create($validated);

        return redirect()->route('admin.master.group.index')
            ->with('success', 'Data group baru berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $group = Group::findOrFail($id);

        return view('admin.master.group.show', [
            'group' => $group
        ]);
    }

    public function edit(string $id)
    {
        $group = Group::findOrFail($id);

        return view('admin.master.group.edit', [
            'group' => $group
        ]);
    }

    public function update(Request $request, string $id)
    {
        $group = Group::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $group->update($validated);

        return redirect()->route('admin.master.group.index')
            ->with('success', 'Data group berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        try {
            $group = Group::findOrFail($id);
            $group->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data group berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $data = Group::query()
                ->orderBy('name', 'asc')
                ->get();

            $fileName = 'Data_Group_' . date('Y-m-d') . '.xlsx';

            return Excel::download(new GroupExport($data), $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export Excel: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            $data = Group::query()
                ->orderBy('name', 'asc')
                ->get();

            $fileName = 'Data_Group_' . date('Y-m-d') . '.pdf';

            $html = view('admin.master.group.pdf', [
                'data' => $data,
                'title' => 'Data Group'
            ])->render();

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('a4', 'portrait');
            $dompdf->render();

            return response()->streamDownload(function() use ($dompdf) {
                echo $dompdf->output();
            }, $fileName, [
                'Content-Type' => 'application/pdf',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export PDF: ' . $e->getMessage());
        }
    }
}
