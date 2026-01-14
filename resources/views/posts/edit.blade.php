<!-- Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="updateModalLabel">Edit Post</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updatePostForm" method="POST">
                    @csrf
                    <input type="hidden" id="up_id" name="up_id" value="">
                    <div class="errMsgContainer"></div>
                    <div class="mb-3">
                        <label for="up_title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="up_title" name="up_title"
                            placeholder="Enter a title" required>
                    </div>
                    <div class="mb-3">
                        <label for="up_description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="up_description" name="up_description"
                            placeholder="Enter a description" required>
                    </div>
                    <div class="mb-3">
                        <label for="up_price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="up_price" name="up_price"
                            placeholder="Enter a price" required step="any">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary update_post">Update</button>
            </div>
        </div>
    </div>
</div>
