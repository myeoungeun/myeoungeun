import argparse
import cv2
import torch
from models.common import DetectMultiBackend
from utils.general import non_max_suppression, xyxy2xywh
from utils.torch_utils import select_device
import time
import subprocess

def run_webcam(weights='best.pt', source=0, imgsz=640, conf_thres=0.25, iou_thres=0.45, device='', view_img=True, run_time=10):
    device = select_device(device)
    model = DetectMultiBackend(weights, device=device)
    imgsz = [imgsz, imgsz] if isinstance(imgsz, int) else imgsz
    cap = cv2.VideoCapture(int(source) if source.isnumeric() else source)

    start_time = time.time()
    person_detected = False
    person_start_time = None

    while cap.isOpened():
        ret, frame = cap.read()
        if not ret:
            break

        img = torch.from_numpy(frame).to(device)
        img = img.half() if model.fp16 else img.float()  # uint8 to fp16/32
        img /= 255.0  # 0 - 255 to 0.0 - 1.0
        if img.ndimension() == 3:
            img = img.unsqueeze(0)

        pred = model(img, augment=False)[0]
        pred = non_max_suppression(pred, conf_thres, iou_thres)[0]

        if pred is not None and len(pred):
            for *xyxy, conf, cls in pred:
                xywh = (xyxy2xywh(torch.tensor(xyxy).view(1, 4)) / img.shape[2]).view(-1).tolist()
                label = f'{model.names[int(cls)]} {conf:.2f}'
                cv2.rectangle(frame, (int(xyxy[0]), int(xyxy[1])), (int(xyxy[2]), int(xyxy[3])), (0, 255, 0), 2)
                cv2.putText(frame, label, (int(xyxy[0]), int(xyxy[1]) - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 255, 0), 2)

                # 객체가 사람이고, 인식 지속 시간이 5초 이상인 경우
                if model.names[int(cls)] == 'person':
                    if not person_detected:
                        person_detected = True
                        person_start_time = time.time()
                    else:
                        if time.time() - person_start_time >= 3:
                            # db.py 실행
                            subprocess.run(["python", "db.py"])
                            print("사람이 3초 이상 인식되었습니다.")
        else:
            person_detected = False
            person_start_time = None

        if view_img:
            cv2.imshow('YOLOv5', frame)
            if cv2.waitKey(1) & 0xFF == ord('q') or time.time() - start_time > run_time:
                break

    cap.release()
    cv2.destroyAllWindows()

def parse_opt():
    parser = argparse.ArgumentParser()
    parser.add_argument('--weights', type=str, default='best.pt', help='model path')
    parser.add_argument('--source', type=str, default='0', help='source')  # 0 for webcam
    parser.add_argument('--imgsz', type=int, default=640, help='inference size (pixels)')
    parser.add_argument('--conf-thres', type=float, default=0.25, help='confidence threshold')
    parser.add_argument('--iou-thres', type=float, default=0.45, help='NMS IoU threshold')
    parser.add_argument('--device', default='', help='cuda device, i.e. 0 or cpu')
    parser.add_argument('--view-img', action='store_true', help='display results')
    parser.add_argument('--run-time', type=int, default=10, help='run time in seconds')
    opt = parser.parse_args()
    return opt

def main(opt):
    run_webcam(weights=opt.weights, source=opt.source, imgsz=opt.imgsz, conf_thres=opt.conf_thres,
               iou_thres=opt.iou_thres, device=opt.device, view_img=opt.view_img, run_time=opt.run_time)

if __name__ == '__main__':
    opt = parse_opt()
    main(opt)
