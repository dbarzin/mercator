<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\MassDestroyDocumentRequest;
use App\Http\Requests\MassStoreDocumentRequest;
use App\Http\Requests\MassUpdateDocumentRequest;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use App\Models\Document;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends APIController
{
    protected string $modelClass = Document::class;

    /**
     * Liste les documents (métadonnées uniquement).
     * GET /api/documents
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return $this->indexResource($request);
    }

    /**
     * Retourne les métadonnées d'un document.
     * GET /api/documents/{document}
     */
    public function show(Document $document): JsonResource
    {
        abort_if(Gate::denies('document_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return $this->asJsonResource($document);
    }

    /**
     * Télécharge le fichier binaire d'un document.
     * GET /api/documents/{document}/download
     */
    public function download(Document $document): StreamedResponse
    {
        abort_if(Gate::denies('document_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $path = storage_path('docs/' . $document->id);
        abort_if(! file_exists($path), Response::HTTP_NOT_FOUND, '404 Not Found');

        // Sanitize Content-Type : on accepte uniquement les MIME types connus
        $mimeType = $this->sanitizeMimeType($document->mimetype);

        // Sanitize filename pour Content-Disposition (RFC 5987)
        $filename = rawurlencode(basename($document->filename));

        return response()->streamDownload(function () use ($path) {
            $handle = fopen($path, 'rb');
            if ($handle !== false) {
                while (! feof($handle)) {
                    echo fread($handle, 8192);
                    ob_flush();
                    flush();
                }
                fclose($handle);
            }
        }, $document->filename, [
            'Cache-Control'             => 'no-cache, private',
            'Content-Description'       => 'File Transfer',
            'Content-Type'              => $mimeType,
            'Content-Length'            => (string) filesize($path),
            'Content-Disposition'       => "attachment; filename=\"{$filename}\"",
            'Content-Transfer-Encoding' => 'binary',
        ]);
    }

    /**
     * Upload d'un nouveau document (multipart/form-data).
     * POST /api/documents
     *
     * Champs : file (required, UploadedFile)
     */
    public function store(StoreDocumentRequest $request): JsonResponse
    {
        abort_if(Gate::denies('document_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $file = $request->file('file');

        Log::debug('API\DocumentController.store : Upload info', [
            'originalName' => $file->getClientOriginalName(),
            'mimeType'     => $file->getClientMimeType(),
            'size'         => $file->getSize(),
        ]);

        $document = new Document;
        $document->filename = $file->getClientOriginalName();
        $document->mimetype = $file->getClientMimeType();
        $document->size     = $file->getSize();
        $document->hash     = hash_file('sha256', $file->path());
        $document->save();

        $file->move(storage_path('docs'), (string) $document->id);

        return response()->json($document, Response::HTTP_CREATED);
    }

    /**
     * Met à jour les métadonnées et/ou remplace le fichier d'un document.
     * POST /api/documents/{document}   (utiliser _method=PUT pour les clients qui ne supportent pas PUT multipart)
     * PUT  /api/documents/{document}   (JSON uniquement, sans remplacement de fichier)
     *
     * Champs optionnels : file (UploadedFile), filename (string)
     */
    public function update(UpdateDocumentRequest $request, Document $document): JsonResponse
    {
        abort_if(Gate::denies('document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Supprimer l'ancien fichier physique
            $oldPath = storage_path('docs/' . $document->id);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }

            $document->filename = $file->getClientOriginalName();
            $document->mimetype = $file->getClientMimeType();
            $document->size     = $file->getSize();
            $document->hash     = hash_file('sha256', $file->path());

            $file->move(storage_path('docs'), (string) $document->id);
        } elseif ($request->has('filename')) {
            // Renommage logique uniquement (le fichier physique reste inchangé)
            $document->filename = $request->input('filename');
        }

        $document->save();

        return response()->json($document);
    }

    /**
     * Supprime un document et son fichier physique.
     * DELETE /api/documents/{document}
     */
    public function destroy(Document $document): Response
    {
        abort_if(Gate::denies('document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $path = storage_path('docs/' . $document->id);
        if (file_exists($path)) {
            unlink($path);
        }

        $this->destroyResource($document);

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Suppression en masse.
     * DELETE /api/documents/mass-destroy
     *
     * Body JSON : { "ids": [1, 2, 3] }
     */
    public function massDestroy(MassDestroyDocumentRequest $request): Response
    {
        abort_if(Gate::denies('document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ids = $request->input('ids', []);

        // Supprimer les fichiers physiques avant de supprimer les enregistrements
        Document::whereIn('id', $ids)->get()->each(function (Document $document) {
            $path = storage_path('docs/' . $document->id);
            if (file_exists($path)) {
                unlink($path);
            }
        });

        $this->massDestroyByIds($ids);

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Création en masse via base64.
     * POST /api/documents/mass-store
     *
     * Body JSON :
     * {
     *   "items": [
     *     {
     *       "filename": "rapport.pdf",
     *       "mimetype": "application/pdf",
     *       "content":  "<base64>"
     *     }
     *   ]
     * }
     */
    public function massStore(MassStoreDocumentRequest $request): JsonResponse
    {
        abort_if(Gate::denies('document_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $createdIds = [];

        foreach ($request->validated()['items'] as $item) {
            $binary = base64_decode($item['content'], strict: true);

            if ($binary === false) {
                return response()->json([
                    'error'    => 'Invalid base64 content',
                    'filename' => $item['filename'],
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $document = new Document;
            $document->filename = basename($item['filename']);
            $document->mimetype = $this->sanitizeMimeType($item['mimetype']);
            $document->size     = strlen($binary);
            $document->hash     = hash('sha256', $binary);
            $document->save();

            $dir = storage_path('docs');
            if (! is_dir($dir)) {
                mkdir($dir, 0750, true);
            }
            file_put_contents($dir . '/' . $document->id, $binary);

            $createdIds[] = $document->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    /**
     * Mise à jour en masse des métadonnées (sans remplacement de fichier).
     * PUT /api/documents/mass-update
     *
     * Body JSON :
     * {
     *   "items": [
     *     { "id": 1, "filename": "nouveau-nom.pdf" }
     *   ]
     * }
     */
    public function massUpdate(MassUpdateDocumentRequest $request): JsonResponse
    {
        abort_if(Gate::denies('document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->massUpdateItems($request->validated()['items']);

        return response()->json(['status' => 'ok']);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Retourne un MIME type sûr : si le type enregistré n'est pas dans la liste
     * blanche, on renvoie application/octet-stream pour éviter tout XSS via
     * Content-Type injection.
     */
    private function sanitizeMimeType(string $mimeType): string
    {
        $allowed = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/zip',
            'application/gzip',
            'application/json',
            'application/xml',
            'text/plain',
            'text/csv',
            'text/html',
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/svg+xml',
            'image/webp',
        ];

        return in_array($mimeType, $allowed, strict: true)
            ? $mimeType
            : 'application/octet-stream';
    }
}