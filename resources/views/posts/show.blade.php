<!-- Modal -->
<div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="showModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="showModalLabel">Show Post</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="showPostForm">
                    <div class="errMsgContainer">
                        <input type="hidden" id="show_id" value="">
                    </div>
                    <div class="mb-3">
                        <label for="show_title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="show_title" aria-describedby="emailHelp"
                            placeholder="Enter a title" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="show_description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="show_description"
                            placeholder="Enter a description" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="show_price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="show_price" placeholder="Enter a price" readonly>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
